<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- MODEL: Order_model ---
// Mengelola data pesanan termasuk transaksi database untuk insert pesanan dan update stok,
// logika filter pesanan berdasarkan tanggal dan status, serta query statistik pendapatan.

class Order_model extends CI_Model {

    // --- FUNGSI: Buat Pesanan ---
    // Melakukan transaksi database: insert data pesanan, insert item pesanan, dan kurangi stok produk.
    // Input: $order_data (array data pesanan), $items_data (array item pesanan).
    // Output: Boolean status transaksi.
    public function create_order($order_data, $items_data) {
        $this->db->trans_start();
        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();
        foreach ($items_data as &$item) { $item['order_id'] = $order_id; }
        $this->db->insert_batch('order_items', $items_data);
        foreach ($items_data as $item) {
            $this->db->set('stock', 'stock - ' . $item['qty'], FALSE);
            $this->db->where('id', $item['product_id']);
            $this->db->update('products');
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // --- FUNGSI: Ambil Pesanan Terfilter ---
    // Mengambil pesanan berdasarkan filter tanggal dan status, diurutkan descending berdasarkan waktu.
    // Input: $date (string opsional), $status (string opsional: 'process', 'all', atau status spesifik).
    // Output: Array objek pesanan.
    public function get_filtered_orders($date = null, $status = null) {
        $this->db->order_by('created_at', 'DESC');
        if ($date) $this->db->where('DATE(created_at)', $date);
        if ($status) {
            if ($status === 'process') $this->db->where_in('status', ['pending', 'cooking']);
            elseif ($status !== 'all') $this->db->where('status', $status);
        }
        return $this->db->get('orders')->result();
    }

    // --- FUNGSI: Ambil Meja Sibuk ---
    // Mengidentifikasi meja yang sedang digunakan berdasarkan status pesanan aktif.
    // Input: Tidak ada.
    // Output: Array nomor meja yang sibuk.
    public function get_busy_tables() {
        $this->db->distinct();
        $this->db->select('table_number');
        $this->db->where_in('status', ['pending', 'cooking', 'ready', 'served']);
        $this->db->where('table_number !=', 'TAKEAWAY');
        $query = $this->db->get('orders');

        $busy = [];
        foreach($query->result() as $row) {
            $busy[] = $row->table_number;
        }
        return $busy;
    }

    // --- FUNGSI: Paksa Kosongkan Meja ---
    // Mengubah status pesanan aktif di meja menjadi 'finished' untuk mengosongkan meja.
    // Input: $table_number (int nomor meja).
    // Output: Boolean hasil update.
    public function force_clear_table($table_number) {
        $this->db->where('table_number', $table_number);
        $this->db->where_in('status', ['pending', 'cooking', 'ready', 'served']);
        return $this->db->update('orders', ['status' => 'finished']);
    }

    // --- FUNGSI: Cek Status Meja ---
    // Memeriksa apakah meja sedang ditempati berdasarkan pesanan aktif.
    // Input: $table_number (string/int nomor meja).
    // Output: Boolean true jika sibuk.
    public function is_table_occupied($table_number) {
        if($table_number == 'TAKEAWAY') return false;
        $this->db->where('table_number', $table_number);
        $this->db->where_in('status', ['pending', 'cooking', 'ready', 'served']);
        return $this->db->count_all_results('orders') > 0;
    }

    // --- FUNGSI: Ambil Semua Pesanan ---
    // Mengambil semua pesanan tanpa filter.
    // Input: Tidak ada.
    // Output: Array objek pesanan.
    public function get_all_orders() { return $this->get_filtered_orders(); }

    // --- FUNGSI: Ambil Pesanan Berdasarkan ID ---
    // Mengambil detail pesanan tunggal.
    // Input: $id (int ID pesanan).
    // Output: Objek pesanan atau null.
    public function get_order_by_id($id) { return $this->db->get_where('orders', ['id' => $id])->row(); }

    // --- FUNGSI: Ambil Item Pesanan ---
    // Mengambil semua item dalam pesanan tertentu.
    // Input: $order_id (int ID pesanan).
    // Output: Array objek item pesanan.
    public function get_order_items($order_id) { return $this->db->get_where('order_items', ['order_id' => $order_id])->result(); }

    // --- FUNGSI: Ambil Ringkasan Item Pesanan ---
    // Membuat string ringkasan item pesanan untuk tampilan.
    // Input: $order_id (int ID pesanan).
    // Output: String ringkasan item.
    public function get_order_items_summary($order_id) {
        $this->db->select('product_name, qty');
        $items = $this->db->get_where('order_items', ['order_id' => $order_id])->result();
        $s = []; foreach ($items as $i) $s[] = $i->qty.'x '.$i->product_name;
        return implode(', ', $s);
    }

    // --- FUNGSI: Update Status Pesanan ---
    // Mengubah status pesanan.
    // Input: $order_id (int ID pesanan), $status (string status baru).
    // Output: Boolean hasil update.
    public function update_status($order_id, $status) {
        $this->db->where('id', $order_id);
        return $this->db->update('orders', ['status' => $status]);
    }

    // --- FUNGSI: Pendapatan Hari Ini ---
    // Menghitung total pendapatan dari pesanan yang sudah disajikan hari ini.
    // Input: Tidak ada.
    // Output: Float total pendapatan.
    public function get_income_today() {
        $this->db->select_sum('total_price');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0;
    }

    // --- FUNGSI: Pendapatan Mingguan ---
    // Menghitung total pendapatan dari pesanan yang sudah disajikan minggu ini.
    // Input: Tidak ada.
    // Output: Float total pendapatan.
    public function get_income_weekly() {
        $this->db->select_sum('total_price');
        $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0;
    }

    // --- FUNGSI: Pendapatan Bulanan ---
    // Menghitung total pendapatan dari pesanan yang sudah disajikan bulan ini.
    // Input: Tidak ada.
    // Output: Float total pendapatan.
    public function get_income_monthly() {
        $this->db->select_sum('total_price');
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0;
    }

    // --- FUNGSI: Pendapatan Berdasarkan Tanggal ---
    // Menghitung total pendapatan dari pesanan yang sudah disajikan pada tanggal tertentu.
    // Input: $d (string tanggal YYYY-MM-DD).
    // Output: Float total pendapatan.
    public function get_income_by_date($d) {
        $this->db->select_sum('total_price');
        $this->db->where('DATE(created_at)', $d);
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0;
    }
}