<!-- A. TAB FILTER STATUS -->
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <!-- Tab: Dalam Proses (Pending + Cooking) -->
        <a href="<?php echo site_url('dashboard/pesanan?status=pending'); ?>" 
           class="<?php echo (!$filter_status || $filter_status == 'pending' || $filter_status == 'process') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-fire-burner"></i> Dalam Proses
        </a>

        <!-- Tab: Selesai (Served) -->
        <a href="<?php echo site_url('dashboard/pesanan?status=served'); ?>" 
           class="<?php echo ($filter_status == 'served') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-check-double"></i> Riwayat Selesai
        </a>
        
        <!-- Tab: Semua -->
        <a href="<?php echo site_url('dashboard/pesanan?status=all'); ?>" 
           class="<?php echo ($filter_status == 'all') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            Semua Data
        </a>
    </nav>
</div>

<!-- B. GRID KARTU PESANAN -->
<?php if(empty($pesanan)): ?>
    <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
        <i class="fa-solid fa-clipboard-list text-6xl mb-4 text-gray-200"></i>
        <p class="text-lg font-medium text-gray-500">Tidak ada pesanan di kategori ini.</p>
        <p class="text-sm text-gray-400">Silakan cek tab lain atau tunggu pesanan baru.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($pesanan as $p): ?>
        
        <?php 
            // --- LOGIKA TAMPILAN MEJA DI KARTU ---
            $meja_display = $p->meja;
            if ($p->meja === 'TAKEAWAY') {
                $meja_display = "Takeaway (Bungkus)";
            } elseif (ctype_digit((string)$p->meja)) {
                $meja_display = "Meja " . $p->meja;
            }

            $status_lower = strtolower($p->status);
            
            // Logic Warna & Tombol
            $status_bg = 'bg-gray-100 text-gray-600';
            $btn_color = 'bg-indigo-600 hover:bg-indigo-700';
            $btn_text = 'Lihat Detail / Sajikan';
            $btn_icon = 'fa-arrow-right';

            if($status_lower == 'pending') $status_bg = 'bg-orange-100 text-orange-700 border border-orange-200';
            elseif($status_lower == 'cooking') $status_bg = 'bg-blue-100 text-blue-700 border border-blue-200';
            elseif($status_lower == 'served') {
                $status_bg = 'bg-green-100 text-green-700 border border-green-200';
                $btn_color = 'bg-green-600 hover:bg-green-700';
                $btn_text = 'Detail Pesanan';
                $btn_icon = 'fa-check';
            }
        ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col h-full animate-fade-in relative group">
            
            <!-- Indikator Warna di Kiri -->
            <div class="absolute left-0 top-0 bottom-0 w-1 <?php echo ($status_lower == 'served') ? 'bg-green-500' : 'bg-orange-500'; ?>"></div>

            <div class="p-5 border-b border-gray-50 bg-white pl-6">
                <div class="flex justify-between items-start mb-2">
                    <!-- TAMPILAN NOMOR MEJA (JUDUL KARTU) -->
                    <h3 class="text-2xl font-bold text-gray-900"><?php echo $meja_display; ?></h3>
                    
                    <span class="text-[10px] font-extrabold px-2 py-1 rounded uppercase tracking-wider <?php echo $status_bg; ?>">
                        <?php echo $p->status; ?>
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-400 font-medium">
                    <span><?php echo $p->order_number; ?></span>
                    <span class="flex items-center gap-1"><i class="fa-regular fa-clock"></i> <?php echo $p->waktu; ?></span>
                </div>
            </div>
            
            <div class="p-5 flex-1 bg-white pl-6">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Menu</p>
                <div class="text-gray-800 font-medium text-sm leading-relaxed line-clamp-3">
                    <?php echo $p->menu; ?>
                </div>
                <?php if(!empty($p->note)): ?>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-start gap-2">
                        <i class="fa-solid fa-note-sticky text-red-400 text-xs mt-0.5"></i> 
                        <span class="text-xs text-red-600 font-medium italic line-clamp-1"><?php echo $p->note; ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="p-4 border-t border-gray-50 pl-6">
                <!-- PENTING: type="button" agar tidak refresh halaman -->
                <button type="button" onclick="openDetailModal(<?php echo $p->id; ?>)" class="w-full <?php echo $btn_color; ?> text-white px-4 py-3 rounded-xl text-sm font-bold transition shadow-sm flex justify-center items-center gap-2 transform group-hover:translate-y-[-2px]">
                    <span><?php echo $btn_text; ?></span>
                    <i class="fa-solid <?php echo $btn_icon; ?>"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODAL DETAIL PESANAN -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-70 transition-opacity backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 py-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all max-w-lg w-full my-8 relative">
            <button type="button" onclick="closeDetailModal()" class="absolute top-4 right-4 text-white hover:text-gray-200 z-10"><i class="fa-solid fa-xmark text-xl"></i></button>
            <div class="bg-indigo-600 px-6 py-6 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider mb-1">Order ID</p>
                    <h3 class="text-2xl font-bold" id="modal-order-id">#ORD-0000</h3>
                </div>
                <i class="fa-solid fa-receipt absolute -right-4 -bottom-4 text-8xl text-indigo-500 opacity-50"></i>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold">Lokasi / Meja</p>
                        <!-- ID INI PENTING BUAT JS DI BAWAH -->
                        <p class="text-xl font-bold text-gray-800" id="modal-table">Loading...</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-xs uppercase font-bold">Total</p>
                        <p class="text-xl font-bold text-green-600" id="modal-total">Rp 0</p>
                    </div>
                </div>

                <div id="modal-items-list" class="space-y-3 mb-6 bg-gray-50 p-4 rounded-xl max-h-60 overflow-y-auto border border-gray-100"></div>

                <div id="modal-note-container" class="hidden mb-6">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 flex items-center gap-2"><i class="fa-solid fa-message"></i> Catatan</h4>
                    <div class="bg-yellow-50 border border-yellow-100 p-3 rounded-lg text-sm text-yellow-800 italic" id="modal-note-text"></div>
                </div>

                <input type="hidden" id="hidden-order-id">
                
                <button type="button" id="btn-complete-order" onclick="completeOrder()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-200 transition">
                    Sajikan Sekarang <i class="fa-solid fa-check ml-2"></i>
                </button>
                
                <div id="status-served-msg" class="hidden text-center p-3 bg-green-50 rounded-xl text-green-700 font-bold border border-green-200">
                    <i class="fa-solid fa-check-circle mr-2"></i> Pesanan Selesai
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(id) {
        // Fetch data dari controller
        fetch('<?php echo site_url("dashboard/get_order_detail/"); ?>' + id)
        .then(response => response.json())
        .then(data => {
            const order = data.order;
            const items = data.items;
            const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

            // 1. Isi ID Order
            document.getElementById('hidden-order-id').value = order.id;
            document.getElementById('modal-order-id').innerText = order.order_number;
            
            // 2. LOGIKA TAMPILAN MEJA DI MODAL
            let tableText = order.table_number;
            if (tableText === 'TAKEAWAY') {
                tableText = "Takeaway (Bungkus)";
            } else if (/^\d+$/.test(tableText)) {
                tableText = "Meja " + tableText;
            }
            // Pastikan elemen ini terisi
            document.getElementById('modal-table').innerText = tableText;
            
            // 3. Isi Total Harga
            document.getElementById('modal-total').innerText = formatter.format(order.total_price);

            // 4. Render Item
            let html = '';
            items.forEach(item => {
                html += `
                <div class="flex justify-between items-center border-b border-gray-200 last:border-0 pb-2 last:pb-0">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-gray-700 bg-white border border-gray-200 w-8 h-8 flex items-center justify-center rounded-lg text-xs shadow-sm">${item.qty}x</span>
                        <span class="text-gray-800 font-medium text-sm">${item.product_name}</span>
                    </div>
                </div>`;
            });
            document.getElementById('modal-items-list').innerHTML = html;

            // 5. Render Catatan
            const noteCont = document.getElementById('modal-note-container');
            if (order.kitchen_note && order.kitchen_note.trim() !== "") {
                document.getElementById('modal-note-text').innerText = order.kitchen_note;
                noteCont.classList.remove('hidden');
            } else {
                noteCont.classList.add('hidden');
            }

            // 6. Tombol Aksi vs Pesan Selesai
            const btn = document.getElementById('btn-complete-order');
            const msg = document.getElementById('status-served-msg');
            
            if (order.status.toLowerCase() === 'served') {
                btn.classList.add('hidden');
                msg.classList.remove('hidden');
            } else {
                btn.classList.remove('hidden');
                msg.classList.add('hidden');
            }

            // Tampilkan Modal
            document.getElementById('detailModal').classList.remove('hidden');
        });
    }

    function closeDetailModal() { document.getElementById('detailModal').classList.add('hidden'); }

    function completeOrder() {
        const id = document.getElementById('hidden-order-id').value;
        showConfirm('Sajikan Pesanan?', 'Pastikan pesanan sudah siap diberikan ke pelanggan.', () => {
            const formData = new FormData();
            formData.append('id', id);
            fetch('<?php echo site_url("dashboard/complete_order"); ?>', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    showPopup('Berhasil!', 'Status pesanan diperbarui.', 'success', () => location.reload());
                } else {
                    showPopup('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                }
            });
        });
    }
</script>