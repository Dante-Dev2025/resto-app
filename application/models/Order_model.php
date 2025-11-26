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

    // [WAJIB ADA] Ambil List Meja yang Sedang Terisi
    public function get_busy_tables() {
        $this->db->distinct();
        $this->db->select('table_number');
        // Meja dianggap sibuk jika statusnya bukan 'served' (selesai)
        $this->db->where_in('status', ['pending', 'cooking', 'ready']);
        $this->db->where('table_number !=', 'TAKEAWAY'); 
        $query = $this->db->get('orders');
        
        $busy = [];
        foreach($query->result() as $row) {
            $busy[] = $row->table_number;
        }
        return $busy;
    }

    // [WAJIB ADA] Paksa Kosongkan Meja
    public function force_clear_table($table_number) {
        $this->db->where('table_number', $table_number);
        // Ubah semua pesanan aktif di meja itu menjadi 'served' (selesai)
        $this->db->where_in('status', ['pending', 'cooking', 'ready']);
        return $this->db->update('orders', ['status' => 'served']);
    }

    // Cek Status Meja (Untuk Self Service)
    public function is_table_occupied($table_number) {
        if($table_number == 'TAKEAWAY') return false;
        $this->db->where('table_number', $table_number);
        $this->db->where_in('status', ['pending', 'cooking', 'ready']);
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
    public function get_income_today() { $this->db->select_sum('total_price'); $this->db->where('DATE(created_at)', date('Y-m-d')); return $this->db->get('orders')->row()->total_price ?? 0; }
    public function get_income_weekly() { $this->db->select_sum('total_price'); $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)'); return $this->db->get('orders')->row()->total_price ?? 0; }
    public function get_income_monthly() { $this->db->select_sum('total_price'); $this->db->where('MONTH(created_at)', date('m')); $this->db->where('YEAR(created_at)', date('Y')); return $this->db->get('orders')->row()->total_price ?? 0; }
    public function get_income_by_date($d) { $this->db->select_sum('total_price'); $this->db->where('DATE(created_at)', $d); return $this->db->get('orders')->row()->total_price ?? 0; }
}