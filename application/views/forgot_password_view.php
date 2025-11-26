<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Resto App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8 border border-gray-100">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-indigo-600">Lupa Password?</h1>
            <p class="text-gray-500 text-sm mt-2">Masukkan email akun Anda untuk mereset password.</p>
        </div>

        <!-- Notifikasi Error -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm border border-red-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('auth/verify_reset'); ?>" method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Terdaftar</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" placeholder="nama@email.com" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition transform active:scale-[0.98] shadow-md">
                Cari Akun
            </button>
        </form>

        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <a href="<?php echo site_url('auth'); ?>" class="text-indigo-600 text-sm font-semibold hover:text-indigo-800 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>

</body>
</html>