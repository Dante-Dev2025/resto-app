<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    // Ambil semua data produk
    public function get_all_products()
    {
        return $this->db->get('products')->result();
    }

    // 1. Fungsi Tambah Produk Baru
    public function insert($data)
    {
        return $this->db->insert('products', $data);
    }

    // 2. Fungsi Update Produk Lama
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    // 3. Fungsi Hapus Produk (BARU)
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }
}