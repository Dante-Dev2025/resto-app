<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // --- TAMBAHKAN BARIS INI JUGA ---
        $this->load->library('session');
        // --------------------------------
        
        $this->load->model('User_model');
    }

    // 1. HALAMAN LOGIN
    public function index() {
        if ($this->session->userdata('logged_in')) {
            $this->redirect_based_on_role();
        } else {
            $this->load->view('login_view');
        }
    }

    // 2. PROSES LOGIN
    public function process_login() {
        // Ambil input dengan XSS Filtering (TRUE)
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->cek_login($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                // Set Session
                $session_data = [
                    'user_id'   => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'role'      => $user->role,
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
                $this->redirect_based_on_role();
            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Email tidak terdaftar!');
            redirect('auth');
        }
    }

    // 3. LOGIC REDIRECT ROLE
    private function redirect_based_on_role() {
        $role = $this->session->userdata('role');
        // Semua role yang valid masuk ke dashboard, logic pemisahan ada di Dashboard.php
        if (in_array($role, ['admin', 'cashier', 'guest'])) {
            redirect('dashboard');
        } else {
            redirect('auth/logout');
        }
    }

    // 4. HALAMAN REGISTER
    public function register() {
        $this->load->view('register_view');
    }

    // 5. PROSES REGISTER
    public function process_register() {
        $name = $this->input->post('name', TRUE);
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        // Enkripsi Password
        $encrypted_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $encrypted_password,
            'role' => 'guest' // Default role
        ];

        if ($this->User_model->register($data)) {
            $this->session->set_flashdata('success', 'Akun berhasil dibuat! Silakan login.');
            redirect('auth');
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftar, email mungkin sudah dipakai.');
            redirect('auth/register');
        }
    }

    // 6. LOGOUT
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }

    // --- FITUR LUPA PASSWORD ---

    public function forgot_password()
    {
        $this->load->view('forgot_password_view');
    }

    public function verify_reset()
    {
        $email = $this->input->post('email', TRUE);
        $user = $this->User_model->cek_login($email);

        if ($user) {
            $data['email_found'] = $email;
            $this->load->view('reset_password_view', $data);
        } else {
            $this->session->set_flashdata('error', 'Email tidak ditemukan dalam sistem.');
            redirect('auth/forgot_password');
        }
    }

    public function process_reset_password()
    {
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');
        $new_hash = password_hash($password, PASSWORD_BCRYPT);

        if ($this->User_model->update_password($email, $new_hash)) {
            $this->session->set_flashdata('success', 'Password berhasil diubah! Silakan login.');
            redirect('auth');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password.');
            redirect('auth/forgot_password');
        }
    }
}