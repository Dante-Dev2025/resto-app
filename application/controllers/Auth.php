<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- CONTROLLER: Auth ---
// Mengelola autentikasi pengguna termasuk login, registrasi, logout, dan reset password.
// Bertanggung jawab atas validasi kredensial, enkripsi password, dan manajemen sesi.

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
    }

    // --- FUNGSI: Halaman Login ---
    // Menampilkan halaman login jika belum login, atau mengarahkan ke dashboard jika sudah login.
    // Input: Tidak ada (cek session).
    // Output: View login atau redirect ke dashboard.
    public function index() {
        if ($this->session->userdata('logged_in')) {
            $this->redirect_based_on_role();
        } else {
            $this->load->view('login_view');
        }
    }

    // --- FUNGSI: Proses Login ---
    // Memvalidasi email dan password, mengatur sesi jika berhasil, atau menampilkan error.
    // Input: email (string), password (string) dari form POST.
    // Output: Redirect ke dashboard atau kembali ke login dengan pesan error.
    public function process_login() {
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->cek_login($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
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

    // --- FUNGSI: Redirect Berdasarkan Role ---
    // Mengarahkan pengguna ke dashboard sesuai role, atau logout jika role tidak valid.
    // Input: Role dari session.
    // Output: Redirect ke dashboard atau logout.
    private function redirect_based_on_role() {
        $role = $this->session->userdata('role');
        if (in_array($role, ['admin', 'cashier', 'guest'])) {
            redirect('dashboard');
        } else {
            redirect('auth/logout');
        }
    }

    // --- FUNGSI: Halaman Registrasi ---
    // Menampilkan form registrasi untuk pengguna baru.
    // Input: Tidak ada.
    // Output: View registrasi.
    public function register() {
        $this->load->view('register_view');
    }

    // --- FUNGSI: Proses Registrasi ---
    // Membuat akun baru dengan role default 'guest', mengenkripsi password.
    // Input: name (string), email (string), password (string) dari form POST.
    // Output: Redirect ke login dengan pesan sukses atau error.
    public function process_register() {
        $name = $this->input->post('name', TRUE);
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $encrypted_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $encrypted_password,
            'role' => 'guest'
        ];

        if ($this->User_model->register($data)) {
            $this->session->set_flashdata('success', 'Akun berhasil dibuat! Silakan login.');
            redirect('auth');
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftar, email mungkin sudah dipakai.');
            redirect('auth/register');
        }
    }

    // --- FUNGSI: Logout ---
    // Menghancurkan sesi dan mengarahkan ke halaman login.
    // Input: Tidak ada.
    // Output: Redirect ke login.
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }

    // --- FUNGSI: Halaman Lupa Password ---
    // Menampilkan form untuk meminta reset password.
    // Input: Tidak ada.
    // Output: View forgot password.
    public function forgot_password()
    {
        $this->load->view('forgot_password_view');
    }

    // --- FUNGSI: Verifikasi Reset Password ---
    // Memeriksa apakah email terdaftar, lalu menampilkan form reset.
    // Input: email (string) dari form POST.
    // Output: View reset password atau redirect dengan error.
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

    // --- FUNGSI: Proses Reset Password ---
    // Mengupdate password baru yang dienkripsi untuk email yang diverifikasi.
    // Input: email (string), password (string) dari form POST.
    // Output: Redirect ke login dengan pesan sukses atau error.
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