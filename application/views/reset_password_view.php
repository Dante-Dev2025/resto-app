<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8 border border-gray-100">
        
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-check text-2xl text-green-600"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Akun Ditemukan!</h1>
            <p class="text-gray-500 text-sm mt-2">Halo, <b><?php echo $email_found; ?></b>.<br>Silakan buat password baru.</p>
        </div>

        <form action="<?php echo site_url('auth/process_reset_password'); ?>" method="post" class="space-y-5">
            
            <!-- Email (Hidden agar terkirim ke controller) -->
            <input type="hidden" name="email" value="<?php echo $email_found; ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition transform active:scale-[0.98] shadow-md">
                Simpan Password Baru
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?php echo site_url('auth'); ?>" class="text-gray-400 text-xs hover:text-gray-600 transition">Batal, kembali ke login</a>
        </div>
    </div>

</body>
</html>