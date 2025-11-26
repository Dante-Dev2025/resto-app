<!-- BAGIAN 1: SUMMARY CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl"><i class="fa-solid fa-calendar-day"></i></div>
        <div>
            <p class="text-gray-500 text-xs uppercase font-bold tracking-wider">Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-800">Rp <?php echo number_format($income_today, 0, ',', '.'); ?></h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xl"><i class="fa-solid fa-calendar-days"></i></div>
        <div>
            <p class="text-gray-500 text-xs uppercase font-bold tracking-wider">Bulan Ini</p>
            <h3 class="text-2xl font-bold text-gray-800">Rp <?php echo number_format($income_monthly, 0, ',', '.'); ?></h3>
        </div>
    </div>
    
    <!-- CARD SPESIAL: INCOME BERDASARKAN FILTER -->
    <div class="bg-indigo-600 p-6 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-4 text-white">
        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-filter"></i></div>
        <div>
            <p class="text-indigo-100 text-xs uppercase font-bold tracking-wider">
                <?php echo $filter_date ? 'Tanggal: '.date('d M Y', strtotime($filter_date)) : 'Semua Waktu'; ?>
            </p>
            <h3 class="text-2xl font-bold">
                <?php 
                    // Jika ada filter, tampilkan income filtered. Jika tidak, tampilkan total semua (atau 0)
                    echo isset($income_filtered) ? 'Rp '.number_format($income_filtered, 0, ',', '.') : '-'; 
                ?>
            </h3>
        </div>
    </div>
</div>

<!-- BAGIAN 2: FILTER & TABEL -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    
    <!-- HEADER TABEL & FILTER -->
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-gray-800">Riwayat Transaksi</h3>
        
        <form action="<?php echo site_url('dashboard/riwayat'); ?>" method="get" class="flex items-center gap-2">
            <div class="relative">
                <i class="fa-solid fa-calendar absolute left-3 top-2.5 text-gray-400"></i>
                <input type="date" name="date" value="<?php echo $filter_date; ?>" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <button type="submit" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                Cari
            </button>
            <?php if($filter_date): ?>
                <a href="<?php echo site_url('dashboard/riwayat'); ?>" class="text-red-500 text-sm hover:underline">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">ID Order</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Meja</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Metode</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if(empty($riwayat)): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada data transaksi ditemukan.</td></tr>
                <?php else: ?>
                    <?php foreach($riwayat as $r): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-bold text-indigo-600"><?php echo $r->order_number; ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('d M Y H:i', strtotime($r->created_at)); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium"><?php echo $r->table_number; ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">Rp <?php echo number_format($r->total_price, 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500 capitalize"><?php echo $r->payment_method; ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if($r->status == 'served'): ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-200">Selesai</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">Proses</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>