<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Use get_instance() to safely access CI resources if $this isn't working or variables aren't passed
$ci =& get_instance();
$role = isset($role) ? $role : $ci->session->userdata('role');
$name = isset($name) ? $name : $ci->session->userdata('name');
$email = isset($email) ? $email : $ci->session->userdata('email'); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Restoran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 3px; }
        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }
        .animate-bounce-in { animation: bounceIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden relative">
        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex md:flex-col justify-between flex-shrink-0 z-30 relative">
            <div>
                <div class="h-16 flex items-center px-6 border-b border-gray-100">
                    <div class="flex items-center gap-2 font-bold text-xl text-gray-800">
                        <i class="fa-solid fa-burger text-indigo-600"></i>
                        <span>RESTO APP</span>
                    </div>
                </div>
                <nav class="mt-6 px-4 space-y-2">
                    
                    <?php if($role === 'admin'): ?>
                        <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Owner Menu</div>
                        
                        <a href="<?php echo site_url('dashboard/stok'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> <span>Stok & Menu</span>
                        </a>
                        
                        <!-- [MENU BARU] UNTUK ADMIN -->
                        <a href="<?php echo site_url('dashboard/meja'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-chair w-5 text-center"></i> <span>Denah Meja</span>
                        </a>

                        <a href="<?php echo site_url('dashboard/riwayat'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i> <span>Riwayat & Income</span>
                        </a>
                        <a href="<?php echo site_url('dashboard/pesanan'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-receipt w-5 text-center"></i> <span>Daftar Pesanan</span>
                        </a>
                        <a href="<?php echo site_url('dashboard/self_service'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-utensils w-5 text-center"></i> <span>Tampilan Pelanggan</span>
                        </a>
                        
                        <div class="pt-4 mt-4 border-t border-gray-100">
                            <a href="<?php echo site_url('dashboard/users'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-xl font-medium transition-colors">
                                <i class="fa-solid fa-users-gear w-5 text-center"></i> <span>Kelola Users</span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($role === 'cashier'): ?>
                        <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Operasional</div>
                        
                        <a href="<?php echo site_url('dashboard/pesanan'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-bell-concierge w-5 text-center"></i> <span>Pesanan Masuk</span>
                        </a>

                        <!-- [MENU BARU] UNTUK CASHIER (DI BAWAH PESANAN) -->
                        <a href="<?php echo site_url('dashboard/meja'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl font-medium transition-colors">
                            <i class="fa-solid fa-chair w-5 text-center"></i> <span>Denah Meja</span>
                        </a>
                        
                    <?php endif; ?>

                    <?php if($role === 'guest'): ?>
                        <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Pelanggan</div>
                        <a href="<?php echo site_url('dashboard/self_service'); ?>" class="flex items-center gap-3 px-4 py-3 bg-indigo-50 text-indigo-600 rounded-xl font-medium transition-colors border border-indigo-100">
                            <i class="fa-solid fa-basket-shopping w-5 text-center"></i> <span>Pesan Makanan</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
            <div class="px-4 py-6 border-t border-gray-100">
                <a href="<?php echo site_url('auth/logout'); ?>" class="flex items-center gap-3 px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> <span>Keluar</span>
                </a>
            </div>
        </aside>

        <!-- KONTEN -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            <!-- HEADER -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-40 shadow-sm flex-shrink-0 relative">
                <div class="flex items-center gap-4">
                    <button class="md:hidden text-gray-600 focus:outline-none"><i class="fa-solid fa-bars text-xl"></i></button>
                    <h1 class="text-lg font-bold text-gray-800 hidden md:block tracking-tight"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
                </div>
                <div class="relative flex items-center gap-3">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-gray-800 leading-tight"><?php echo $name ? $name : 'User System'; ?></div>
                        <div class="mt-0.5"><span class="px-3 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border bg-indigo-50 text-indigo-600 border-indigo-100"><?php echo $role ? $role : 'Guest'; ?></span></div>
                    </div>
                    <button onclick="document.getElementById('profile-popup').classList.toggle('hidden')" class="w-10 h-10 rounded-full bg-indigo-600 text-white font-bold text-lg shadow-md hover:shadow-lg transition"><?php echo $name ? substr($name, 0, 1) : 'U'; ?></button>
                    <div id="profile-popup" class="hidden absolute right-0 top-full mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 p-6 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold mb-3"><?php echo $name ? substr($name, 0, 1) : 'U'; ?></div>
                        <h3 class="font-bold text-gray-800"><?php echo $name; ?></h3>
                        <p class="text-xs text-gray-500 mb-4"><?php echo $email; ?></p>
                        <a href="<?php echo site_url('auth/logout'); ?>" class="text-sm text-red-500 font-bold hover:underline">Keluar Aplikasi</a>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 z-0 relative">
                <?php if (isset($content) && $content !== '') { $this->load->view($content); } ?>
            </main>
        </div>
    </div>

    <!-- GLOBAL POPUPS (ALERT & CONFIRM) -->
    <div id="globalAlert" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-70 transition-opacity backdrop-blur-sm"></div>
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-sm w-full p-6 text-center animate-bounce-in relative z-[110]">
                <div id="alert-icon-bg" class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i id="alert-icon" class="fa-solid fa-check text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2" id="alert-title">Berhasil!</h3>
                <p class="text-sm text-gray-500 mb-6" id="alert-message">Pesan sukses disini.</p>
                <button id="alert-btn" onclick="closeGlobalAlert()" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl transition transform active:scale-95">Oke, Mengerti</button>
            </div>
        </div>
    </div>

    <div id="globalConfirm" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-70 transition-opacity backdrop-blur-sm"></div>
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-sm w-full p-6 text-center animate-bounce-in relative z-[110]">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <i class="fa-solid fa-question text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2" id="confirm-title">Konfirmasi</h3>
                <p class="text-sm text-gray-500 mb-6" id="confirm-message">Apakah Anda yakin?</p>
                <div class="flex gap-3">
                    <button onclick="closeGlobalConfirm(false)" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-50 transition">Batal</button>
                    <button id="btn-confirm-yes" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Ya, Yakin</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let alertCallback = null;
        function showPopup(title, message, type = 'success', callback = null) {
            const modal = document.getElementById('globalAlert');
            const iconBg = document.getElementById('alert-icon-bg');
            const icon = document.getElementById('alert-icon');
            
            document.getElementById('alert-title').innerText = title;
            document.getElementById('alert-message').innerText = message;
            alertCallback = callback;

            if (type === 'success') {
                iconBg.className = "mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4";
                icon.className = "fa-solid fa-check text-3xl text-green-600";
            } else {
                iconBg.className = "mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4";
                icon.className = "fa-solid fa-xmark text-3xl text-red-600";
            }
            modal.classList.remove('hidden');
        }

        function closeGlobalAlert() {
            document.getElementById('globalAlert').classList.add('hidden');
            if (alertCallback) { alertCallback(); alertCallback = null; }
        }

        let confirmCallback = null;
        function showConfirm(title, message, callback) {
            document.getElementById('confirm-title').innerText = title;
            document.getElementById('confirm-message').innerText = message;
            confirmCallback = callback;
            document.getElementById('globalConfirm').classList.remove('hidden');
        }

        function closeGlobalConfirm(result) {
            document.getElementById('globalConfirm').classList.add('hidden');
            if (result && confirmCallback) confirmCallback();
        }
        document.getElementById('btn-confirm-yes').onclick = () => closeGlobalConfirm(true);
    </script>
</body>
</html>