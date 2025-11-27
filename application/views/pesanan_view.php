<!-- BAGIAN 1: TAB FILTER STATUS -->
<!-- Menampilkan tab untuk memfilter pesanan berdasarkan status.
     Logika: Tab "Dalam Proses" menampilkan pesanan yang belum selesai (pending, cooking, ready).
     Tab "Riwayat Selesai" menampilkan pesanan yang sudah disajikan atau selesai (served, finished). -->
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <!-- Tab: Dalam Proses -->
        <a href="<?php echo site_url('dashboard/pesanan?status=process'); ?>"
           class="<?php echo (!$filter_status || $filter_status == 'process') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-fire-burner"></i> Dalam Proses
        </a>

        <!-- Tab: Selesai -->
        <a href="<?php echo site_url('dashboard/pesanan?status=served'); ?>"
           class="<?php echo ($filter_status == 'served') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-check-double"></i> Riwayat Selesai
        </a>

        <a href="<?php echo site_url('dashboard/pesanan?status=all'); ?>"
           class="<?php echo ($filter_status == 'all') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            Semua Data
        </a>
    </nav>
</div>

<!-- BAGIAN 2: GRID KARTU PESANAN -->
<!-- Menampilkan kartu pesanan dalam grid.
     Logika: Setiap kartu menunjukkan detail pesanan dengan styling dinamis berdasarkan status.
     Jika tidak ada pesanan, tampilkan pesan kosong. -->
<?php if(empty($pesanan)): ?>
    <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
        <i class="fa-solid fa-clipboard-list text-6xl mb-4 text-gray-200"></i>
        <p class="text-lg font-medium text-gray-500">Tidak ada pesanan di kategori ini.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($pesanan as $p): ?>

        <?php
            // Logika tampilan meja: TAKEAWAY ditampilkan sebagai "Takeaway (Bungkus)", angka sebagai "Meja X"
            $meja_display = $p->meja;
            if ($p->meja === 'TAKEAWAY') {
                $meja_display = "Takeaway (Bungkus)";
            } elseif (ctype_digit((string)$p->meja)) {
                $meja_display = "Meja " . $p->meja;
            }

            $status_lower = strtolower($p->status);
            // Patch: Jika status kosong, anggap finished
            if(empty($status_lower)) $status_lower = 'finished';

            // Logika styling status: Warna background, tombol, dan ikon berdasarkan status pesanan
            $status_bg = 'bg-gray-100 text-gray-600';
            $btn_color = 'bg-gray-200 text-gray-500 cursor-not-allowed';
            $btn_text = 'Status Tidak Dikenal';
            $btn_icon = 'fa-question';

            if($status_lower == 'pending') {
                $status_bg = 'bg-orange-100 text-orange-700 border border-orange-200';
                $btn_color = 'bg-orange-600 hover:bg-orange-700 text-white shadow-orange-200';
                $btn_text = 'Lihat Detail / Proses';
                $btn_icon = 'fa-arrow-right';
            }
            elseif($status_lower == 'cooking') {
                $status_bg = 'bg-blue-100 text-blue-700 border border-blue-200';
                $btn_color = 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-200';
                $btn_text = 'Lihat Detail / Sajikan';
                $btn_icon = 'fa-utensils';
            }
            elseif($status_lower == 'ready') {
                $status_bg = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                $btn_color = 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-yellow-200';
                $btn_text = 'Siap Disajikan';
                $btn_icon = 'fa-bell';
            }
            elseif($status_lower == 'served') {
                $status_bg = 'bg-green-100 text-green-700 border border-green-200';
                $btn_color = 'bg-green-600 hover:bg-green-700 text-white shadow-green-200';
                $btn_text = 'Detail Pesanan (Aktif)';
                $btn_icon = 'fa-check';
            }
            elseif($status_lower == 'finished') {
                $status_bg = 'bg-gray-100 text-gray-500 border border-gray-200';
                $btn_color = 'bg-gray-100 text-gray-400 border border-gray-200 hover:bg-gray-200';
                $btn_text = 'Transaksi Selesai';
                $btn_icon = 'fa-archive';
            }
        ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col h-full animate-fade-in relative group">

            <!-- Indikator Warna Kiri: Menunjukkan status dengan warna strip -->
            <div class="absolute left-0 top-0 bottom-0 w-1 <?php
                if($status_lower == 'served') echo 'bg-green-500';
                elseif($status_lower == 'finished') echo 'bg-gray-400';
                elseif($status_lower == 'ready') echo 'bg-yellow-500';
                else echo 'bg-orange-500';
            ?>"></div>

            <div class="p-5 border-b border-gray-50 bg-white pl-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-2xl font-bold text-gray-900"><?php echo $meja_display; ?></h3>
                    <!-- Tampilkan Status, jika kosong tulis Selesai -->
                    <span class="text-[10px] font-extrabold px-2 py-1 rounded uppercase tracking-wider <?php echo $status_bg; ?>">
                        <?php echo $p->status ? $p->status : 'FINISHED'; ?>
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
                <button type="button" onclick="openDetailModal(<?php echo $p->id; ?>)" class="w-full <?php echo $btn_color; ?> px-4 py-3 rounded-xl text-sm font-bold transition shadow-sm flex justify-center items-center gap-2 transform group-hover:translate-y-[-2px]">
                    <span><?php echo $btn_text; ?></span>
                    <i class="fa-solid <?php echo $btn_icon; ?>"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODAL DETAIL -->
<!-- Modal untuk menampilkan detail pesanan dan mengupdate status.
     Logika: Mengambil data dari API, menampilkan item, catatan, dan tombol aksi berdasarkan status. -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-70 transition-opacity backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 py-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all max-w-lg w-full my-8 relative">
            <button type="button" onclick="closeDetailModal()" class="absolute top-4 right-4 text-white hover:text-gray-200 z-10"><i class="fa-solid fa-xmark text-xl"></i></button>

            <div class="bg-indigo-600 px-6 py-6 text-white relative overflow-hidden" id="modal-header-bg">
                <div class="relative z-10">
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider mb-1">Order ID</p>
                    <h3 class="text-2xl font-bold" id="modal-order-id">#ORD-0000</h3>
                </div>
                <i class="fa-solid fa-receipt absolute -right-4 -bottom-4 text-8xl text-indigo-500 opacity-50"></i>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold">Lokasi</p>
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

                <div id="status-msg-container" class="hidden text-center p-3 rounded-xl font-bold border"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi: openDetailModal
    // Membuka modal detail pesanan.
    // Input: id pesanan.
    // Tujuan: Mengambil data dari server dan mengisi modal dengan detail pesanan.
    function openDetailModal(id) {
        fetch('<?php echo site_url("dashboard/get_order_detail/"); ?>' + id)
        .then(response => response.json())
        .then(data => {
            const order = data.order;
            const items = data.items;
            const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

            document.getElementById('hidden-order-id').value = order.id;
            document.getElementById('modal-order-id').innerText = order.order_number;

            let tableText = order.table_number;
            if (tableText === 'TAKEAWAY') tableText = "Takeaway";
            else if (/^\d+$/.test(tableText)) tableText = "Meja " + tableText;
            document.getElementById('modal-table').innerText = tableText;
            document.getElementById('modal-total').innerText = formatter.format(order.total_price);

            let html = '';
            items.forEach(item => {
                html += `<div class="flex justify-between items-center border-b border-gray-200 last:border-0 pb-2 last:pb-0"><div class="flex items-center gap-3"><span class="font-bold text-gray-700 bg-white border border-gray-200 w-8 h-8 flex items-center justify-center rounded-lg text-xs shadow-sm">${item.qty}x</span><span class="text-gray-800 font-medium text-sm">${item.product_name}</span></div></div>`;
            });
            document.getElementById('modal-items-list').innerHTML = html;

            const noteCont = document.getElementById('modal-note-container');
            if (order.kitchen_note && order.kitchen_note.trim() !== "") {
                document.getElementById('modal-note-text').innerText = order.kitchen_note;
                noteCont.classList.remove('hidden');
            } else { noteCont.classList.add('hidden'); }

            // Logika status: Menampilkan tombol atau pesan berdasarkan status pesanan
            const btn = document.getElementById('btn-complete-order');
            const msgContainer = document.getElementById('status-msg-container');

            // Patch: Jika status kosong, anggap finished
            let status = order.status ? order.status.toLowerCase() : 'finished';

            btn.classList.add('hidden');
            msgContainer.classList.add('hidden');

            if (status === 'pending') {
                btn.innerHTML = 'Sajikan <i class="fa-solid fa-fire-burner ml-2"></i>';
                btn.className = "w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-xl shadow-lg transition";
                btn.classList.remove('hidden');
            }
            else if (status === 'cooking') {
                btn.innerHTML = 'Sajikan Sekarang <i class="fa-solid fa-check ml-2"></i>';
                btn.className = "w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transition";
                btn.classList.remove('hidden');
            }
            else if (status === 'ready') {
                btn.innerHTML = 'Antar ke Meja <i class="fa-solid fa-bell ml-2"></i>';
                btn.className = "w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 rounded-xl shadow-lg transition";
                btn.classList.remove('hidden');
            }
            else if (status === 'served') {
                msgContainer.innerHTML = '<i class="fa-solid fa-utensils mr-2"></i> Sedang Disajikan / Dimakan';
                msgContainer.className = "text-center p-3 bg-green-50 text-green-700 border border-green-200 rounded-xl font-bold block";
                msgContainer.classList.remove('hidden');
            }
            else if (status === 'finished') {
                msgContainer.innerHTML = '<i class="fa-solid fa-check-double mr-2"></i> Transaksi Selesai (Meja Kosong)';
                msgContainer.className = "text-center p-3 bg-gray-100 text-gray-500 border border-gray-200 rounded-xl font-bold block";
                msgContainer.classList.remove('hidden');
            }

            document.getElementById('detailModal').classList.remove('hidden');
        });
    }

    // Fungsi: closeDetailModal
    // Menutup modal detail.
    function closeDetailModal() { document.getElementById('detailModal').classList.add('hidden'); }

    // Fungsi: completeOrder
    // Mengupdate status pesanan melalui AJAX.
    // Tujuan: Memproses perubahan status pesanan dan memberikan feedback.
    function completeOrder() {
        const id = document.getElementById('hidden-order-id').value;
        showConfirm('Update Status?', 'Lanjutkan proses pesanan ini?', () => {
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
