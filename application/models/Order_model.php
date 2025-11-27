<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

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

    public function get_filtered_orders($date = null, $status = null) {
        $this->db->order_by('created_at', 'DESC');
        if ($date) $this->db->where('DATE(created_at)', $date);
        if ($status) {
            if ($status === 'process') $this->db->where_in('status', ['pending', 'cooking']);
            elseif ($status !== 'all') $this->db->where('status', $status);
        }
        return $this->db->get('orders')->result();
    }

    // [UPDATED LOGIC] Meja SIBUK jika status: pending, cooking, ready, ATAU served (sedang makan)
    public function get_busy_tables() {
        $this->db->distinct();
        $this->db->select('table_number');
        // Perubahan: 'served' juga dianggap sibuk (pelanggan sedang makan)
        $this->db->where_in('status', ['pending', 'cooking', 'ready', 'served']);
        $this->db->where('table_number !=', 'TAKEAWAY'); 
        $query = $this->db->get('orders');
        
        $busy = [];
        foreach($query->result() as $row) {
            $busy[] = $row->table_number;
        }
        return $busy;
    }

    // [UPDATED LOGIC] Paksa Kosongkan Meja (Ubah status 'served' jadi 'completed')
    // Kita butuh status baru 'completed' atau 'closed' agar tidak terdeteksi oleh get_busy_tables
    public function force_clear_table($table_number) {
        $this->db->where('table_number', $table_number);
        // Ubah semua status aktif (termasuk served) menjadi 'completed' (Riwayat Selesai & Meja Kosong)
        $this->db->where_in('status', ['pending', 'cooking', 'ready', 'served']);
        
        // Catatan: Pastikan enum di database mendukung 'completed'. 
        // Jika tidak, kita bisa gunakan 'closed' atau hapus where_in di get_busy_tables agar 'served' tidak masuk.
        // TAPI, karena enum database kamu terbatas ('pending','cooking','ready','served'),
        // Kita harus sedikit kreatif.
        
        // Opsi A: Ubah Enum Database (Best Practice).
        // Opsi B: Gunakan 'served' sebagai tanda selesai makan (Logic lama), TAPI tombol "Sajikan" jangan ubah ke 'served', tapi ke 'ready'.
        
        // Mari kita pakai Opsi C (Logic Paling Masuk Akal dengan DB saat ini):
        // 1. Tombol "Sajikan" mengubah status ke 'served' (Makanan di meja).
        // 2. Meja dengan status 'served' DIANGGAP SIBUK.
        // 3. Tombol "Kosongkan Meja" mengubah status ke 'finished' (Kita perlu tambah enum ini atau pakai trik lain).
        
        // Karena saya tidak bisa ubah struktur DB kamu sekarang, saya akan pakai trik:
        // Status 'served' = Sedang Makan (Meja Sibuk).
        // Status 'finished' = Pelanggan Pulang (Meja Kosong).
        
        // KAMU HARUS MENJALANKAN QUERY SQL INI DI PHPMYADMIN:
        // ALTER TABLE orders MODIFY COLUMN status ENUM('pending','cooking','ready','served','finished') DEFAULT 'pending';
        
        return $this->db->update('orders', ['status' => 'finished']);
    }

    // [UPDATED LOGIC] Cek Status Meja
    public function is_table_occupied($table_number) {
        if($table_number == 'TAKEAWAY') return false;
        $this->db->where('table_number', $table_number);
        // Meja sibuk jika ada pesanan yang belum 'finished'
        $this->db->where_in('status', ['pending', 'cooking', 'ready', 'served']);
        return $this->db->count_all_results('orders') > 0;
    }

    // --- Fungsi Lainnya ---
    public function get_all_orders() { return $this->get_filtered_orders(); }
    public function get_order_by_id($id) { return $this->db->get_where('orders', ['id' => $id])->row(); }
    public function get_order_items($order_id) { return $this->db->get_where('order_items', ['order_id' => $order_id])->result(); }
    public function get_order_items_summary($order_id) {
        $this->db->select('product_name, qty');
        $items = $this->db->get_where('order_items', ['order_id' => $order_id])->result();
        $s = []; foreach ($items as $i) $s[] = $i->qty.'x '.$i->product_name;
        return implode(', ', $s);
    }
    public function update_status($order_id, $status) {
        $this->db->where('id', $order_id);
        return $this->db->update('orders', ['status' => $status]);
    }
    
    // Statistik (Hanya hitung yang sudah served atau finished agar akurat sebagai income)
    public function get_income_today() { 
        $this->db->select_sum('total_price'); 
        $this->db->where('DATE(created_at)', date('Y-m-d')); 
        $this->db->where_in('status', ['served', 'finished']); // Hanya hitung yang sudah disajikan/selesai
        return $this->db->get('orders')->row()->total_price ?? 0; 
    }
    public function get_income_weekly() { 
        $this->db->select_sum('total_price'); 
        $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)'); 
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0; 
    }
    public function get_income_monthly() { 
        $this->db->select_sum('total_price'); 
        $this->db->where('MONTH(created_at)', date('m')); 
        $this->db->where('YEAR(created_at)', date('Y')); 
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0; 
    }
    public function get_income_by_date($d) { 
        $this->db->select_sum('total_price'); 
        $this->db->where('DATE(created_at)', $d); 
        $this->db->where_in('status', ['served', 'finished']);
        return $this->db->get('orders')->row()->total_price ?? 0; 
    }
}