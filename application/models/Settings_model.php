<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- MODEL: Settings_model ---
// Mengelola penyimpanan konfigurasi dinamis seperti jumlah meja.

class Settings_model extends CI_Model {

    // --- FUNGSI: Ambil Setting ---
    // Mengambil nilai setting berdasarkan kunci.
    // Input: $key (string kunci setting).
    // Output: String nilai setting atau null.
    public function get_setting($key) {
        $query = $this->db->get_where('settings', ['setting_key' => $key]);
        return $query->row()->setting_value ?? null;
    }

    // --- FUNGSI: Update Setting ---
    // Mengupdate nilai setting berdasarkan kunci, insert jika belum ada.
    // Input: $key (string kunci setting), $value (string nilai baru).
    // Output: Boolean hasil operasi.
    public function update_setting($key, $value) {
        $this->db->where('setting_key', $key);
        $query = $this->db->get('settings');

        if ($query->num_rows() > 0) {
            $this->db->where('setting_key', $key);
            return $this->db->update('settings', ['setting_value' => $value]);
        } else {
            return $this->db->insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    // --- FUNGSI: Ambil Total Meja ---
    // Mengambil jumlah total meja dari setting, default 20 jika belum diset.
    // Input: Tidak ada.
    // Output: Int jumlah meja.
    public function get_total_tables() {
        $val = $this->get_setting('total_tables');
        return $val ? intval($val) : 20;
    }
}