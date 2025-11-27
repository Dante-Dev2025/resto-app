<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- MODEL: Product_model ---
// Mengelola operasi CRUD dasar untuk data menu produk.

class Product_model extends CI_Model {

    // --- FUNGSI: Ambil Semua Produk ---
    // Mengambil semua data produk dari database.
    // Input: Tidak ada.
    // Output: Array objek produk.
    public function get_all_products()
    {
        return $this->db->get('products')->result();
    }

    // --- FUNGSI: Tambah Produk ---
    // Menambahkan produk baru ke database.
    // Input: $data (array data produk).
    // Output: Boolean hasil insert.
    public function insert($data)
    {
        return $this->db->insert('products', $data);
    }

    // --- FUNGSI: Update Produk ---
    // Mengupdate data produk berdasarkan ID.
    // Input: $id (int ID produk), $data (array data baru).
    // Output: Boolean hasil update.
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    // --- FUNGSI: Hapus Produk ---
    // Menghapus produk dari database berdasarkan ID.
    // Input: $id (int ID produk).
    // Output: Boolean hasil delete.
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }
}