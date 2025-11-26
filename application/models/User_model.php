<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function cek_login($email)
    {
        $this->db->where('email', $email);
        return $this->db->get('users')->row();
    }

    public function register($data)
    {
        return $this->db->insert('users', $data);
    }

    public function get_all_users()
    {
        $this->db->select('id, name, email, role, created_at');
        return $this->db->get('users')->result();
    }

    public function update_role($user_id, $new_role)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['role' => $new_role]);
    }

    // --- [BARU] FUNGSI GANTI PASSWORD ---
    public function update_password($email, $new_password_hash)
    {
        $this->db->where('email', $email);
        return $this->db->update('users', ['password' => $new_password_hash]);
    }
}