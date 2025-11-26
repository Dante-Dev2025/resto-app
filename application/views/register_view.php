<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - My App</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">

    <!-- Card Container -->
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden flex h-[650px]">
        
        <!-- BAGIAN KIRI: Form Register -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
            
            <div class="mb-6">
                <div class="flex items-center gap-2 font-bold text-2xl text-indigo-600 mb-2">
                    <i class="fa-solid fa-shapes"></i>
                    <span>MY APP</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Buat Akun Baru</h1>
                <p class="text-gray-500 mt-2">Lengkapi data diri Anda untuk bergabung.</p>
            </div>

            <!-- Notifikasi Error (Jika Email sudah terdaftar) -->
            <?php if($this->session->flashdata('error')): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm border border-red-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Form Register -->
            <form action="<?php echo site_url('auth/process_register'); ?>" method="post" class="space-y-4">
                
                <!-- Input Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <input type="text" name="name" placeholder="Nama Anda" 
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" required>
                    </div>
                </div>

                <!-- Input Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" placeholder="contoh@email.com" 
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
                        <input type="password" name="password" placeholder="Buat password aman" 
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" required>
                    </div>
                </div>

                <!-- Button Register -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 mt-2">
                    Daftar Sekarang
                </button>

            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                Sudah punya akun? 
                <a href="<?php echo site_url('auth'); ?>" class="text-indigo-600 font-semibold hover:underline">Login di sini</a>
            </div>

            <div class="mt-auto pt-4 text-center text-xs text-gray-400">
                &copy; 2024 My App System. All rights reserved.
            </div>
        </div>

        <!-- BAGIAN KANAN: Gambar / Banner (Sama dengan Login tapi beda warna overlay dikit biar fresh) -->
        <div class="hidden md:block w-1/2 bg-green-600 relative overflow-hidden">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                 alt="Meeting Background" 
                 class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-multiply">
            
            <!-- Overlay Content -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-12 text-center z-10">
                <div class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl mb-6 inline-block">
                    <i class="fa-solid fa-user-plus text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4">Bergabung Bersama Kami</h2>
                <p class="text-green-100 text-lg leading-relaxed">
                    Daftarkan diri Anda sekarang dan mulai kelola pesanan dengan lebih efisien dan cepat.
                </p>

                <!-- Dekorasi lingkaran -->
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            </div>
        </div>

    </div>

</body>
</html>