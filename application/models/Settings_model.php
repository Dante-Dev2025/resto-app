<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    // Get a specific setting value by key
    public function get_setting($key) {
        $query = $this->db->get_where('settings', ['setting_key' => $key]);
        return $query->row()->setting_value ?? null;
    }

    // Update a specific setting value by key
    public function update_setting($key, $value) {
        // Check if the key exists first
        $this->db->where('setting_key', $key);
        $query = $this->db->get('settings');

        if ($query->num_rows() > 0) {
            // Update existing
            $this->db->where('setting_key', $key);
            return $this->db->update('settings', ['setting_value' => $value]);
        } else {
            // Insert new if not exists (safety fallback)
            return $this->db->insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
    }
    
    // Helper to get total tables, defaulting to 20 if not set
    public function get_total_tables() {
        $val = $this->get_setting('total_tables');
        return $val ? intval($val) : 20; 
    }
}