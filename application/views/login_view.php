<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resto App</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Animasi tambahan untuk background blobs */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">

    <!-- Card Container -->
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden flex h-auto min-h-[600px]">
        
        <!-- BAGIAN KIRI: Form Login -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
            
            <div class="mb-6">
                <div class="flex items-center gap-2 font-bold text-2xl text-indigo-600 mb-2">
                    <i class="fa-solid fa-utensils"></i> <!-- Mengganti icon shapes dengan utensils agar sesuai konteks Resto -->
                    <span>RESTO APP</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
                <p class="text-gray-500 mt-2">Silakan masuk untuk mengelola restoran Anda.</p>
            </div>

            <!-- LOGIC: Menampilkan Pesan Error (Flashdata) -->
            <?php if($this->session->flashdata('error')): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm border border-red-100 flex items-center gap-2 animate-pulse">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <!-- LOGIC: Menampilkan Pesan Sukses (Flashdata) -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg mb-4 text-sm border border-green-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <!-- LOGIC: Action diarahkan ke auth/process_login -->
            <form action="<?php echo site_url('auth/process_login'); ?>" method="post" class="space-y-5">
                
                <!-- Input Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <!-- LOGIC: name="email" added -->
                        <input type="email" name="email" placeholder="nama@email.com" 
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" required>
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <!-- LOGIC: name="password" added -->
                        <input type="password" name="password" placeholder="••••••••" 
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" required>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password (UI Only - sesuaikan logic jika ada) -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="text-gray-600">Ingat saya</span>
                    </label>
                    <!-- Link lupa password dikosongkan karena tidak ada di logic sumber -->
                    <a href="<?php echo site_url('auth/forgot_password'); ?>" class="text-indigo-600 font-semibold text-sm hover:underline">Lupa Password?</a>    
                </div>

                <!-- Button Login -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>

            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                Belum punya akun? 
                <!-- LOGIC: Link diarahkan ke auth/register -->
                <a href="<?php echo site_url('auth/register'); ?>" class="text-indigo-600 font-semibold hover:underline">Buat Akun Guest</a>
            </div>

            <div class="mt-auto pt-6 text-center text-xs text-gray-400">
                &copy; <?php echo date('Y'); ?> Resto App System. All rights reserved.
            </div>
        </div>

        <!-- BAGIAN KANAN: Gambar / Banner -->
        <div class="hidden md:block w-1/2 bg-indigo-600 relative overflow-hidden">
            <!-- Background Image (Diganti dengan gambar tema restoran) -->
            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                 alt="Restaurant Background" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-multiply">
            
            <!-- Overlay Content -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-12 text-center z-10">
                <div class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl mb-6 inline-block">
                    <!-- Icon diganti tema makanan/chart -->
                    <i class="fa-solid fa-chart-pie text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4">Kelola Restoran Lebih Mudah</h2>
                <p class="text-indigo-100 text-lg leading-relaxed">
                    Sistem manajemen terintegrasi untuk memantau pesanan, stok, dan laporan penjualan dalam satu dashboard.
                </p>

                <!-- Dekorasi lingkaran -->
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            </div>
        </div>

    </div>

</body>
</html>