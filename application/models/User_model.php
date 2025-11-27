<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function cek_login($email)
    {
        $this->db->where('email', $email);
        return $this->db->get('users')->row();
    }

    // [UPDATED] Register dengan Pengecekan Duplikat
    public function register($data)
    {
        // 1. Cek dulu apakah email sudah ada di database
        $this->db->where('email', $data['email']);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return FALSE; // Gagal: Email sudah ada
        }

        // 2. Jika tidak ada, baru jalankan insert
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

    public function update_password($email, $new_password_hash)
    {
        $this->db->where('email', $email);
        return $this->db->update('users', ['password' => $new_password_hash]);
    }
}