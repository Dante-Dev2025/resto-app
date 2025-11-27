<!-- --- VIEW: Meja View ---
Menampilkan grid denah meja dengan status visual (kosong/merah terisi),
kontrol admin untuk menambah/kurangi jumlah meja, dan fungsi JS untuk mengosongkan meja. -->

<div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Meja</h2>
        <p class="text-gray-500 text-sm">Klik meja berwarna <span class="font-bold text-red-500">MERAH</span> untuk mengosongkannya.</p>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- KONTROL ADMIN UNTUK JUMLAH MEJA -->
        <?php if($this->session->userdata('role') == 'admin'): ?>
        <div class="flex items-center bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
            <button onclick="modifyTable('reduce')" class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition" title="Kurangi Meja">
                <i class="fa-solid fa-minus"></i>
            </button>
            <span class="px-3 font-bold text-gray-700 text-sm">Total: <?php echo $total_tables; ?></span>
            <button onclick="modifyTable('add')" class="w-8 h-8 flex items-center justify-center text-green-600 hover:bg-green-50 rounded-lg transition" title="Tambah Meja">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <?php endif; ?>

        <!-- Legend Status -->
        <div class="flex gap-4 bg-white p-3 rounded-xl border border-gray-200 shadow-sm hidden sm:flex">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-white border-2 border-gray-300 rounded shadow-sm"></div>
                <span class="text-xs font-bold text-gray-600">Kosong</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-500 rounded shadow-sm shadow-red-200"></div>
                <span class="text-xs font-bold text-gray-600">Terisi</span>
            </div>
        </div>
    </div>
</div>

<!-- Grid Denah Meja -->
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
    <?php for($i = 1; $i <= $total_tables; $i++): ?>
        <?php 
            // Cek status meja
            $is_busy = in_array($i, $busy_tables);
            
            // Styling dinamis
            $bg_class = $is_busy ? 'bg-red-500 text-white shadow-red-200 hover:bg-red-600' : 'bg-white text-gray-700 border-2 border-gray-100 hover:border-indigo-500 hover:text-indigo-600';
            $icon_class = $is_busy ? 'text-white opacity-80' : 'text-gray-300';
            $status_text = $is_busy ? 'Terisi' : 'Available';
            $cursor = $is_busy ? "cursor-pointer" : "cursor-default";
            
            // Jika terisi, klik akan memanggil fungsi modal
            $onclick = $is_busy ? "onclick=\"showTableOptions($i)\"" : "";
        ?>
        
        <div <?php echo $onclick; ?> class="relative <?php echo $bg_class; ?> <?php echo $cursor; ?> rounded-2xl p-6 flex flex-col items-center justify-center gap-2 shadow-sm transition duration-200 group h-40">
            
            <!-- Nomor Meja -->
            <span class="text-4xl font-extrabold tracking-tighter"><?php echo $i; ?></span>
            
            <!-- Ikon Kursi -->
            <i class="fa-solid fa-chair text-3xl <?php echo $icon_class; ?> mb-1 transition group-hover:scale-110"></i>
            
            <!-- Label Status -->
            <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded bg-black/10">
                <?php echo $status_text; ?>
            </span>

            <?php if($is_busy): ?>
                <!-- Hover Effect -->
                <div class="absolute inset-0 bg-red-900/90 rounded-2xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200 backdrop-blur-sm">
                    <i class="fa-solid fa-gear text-3xl text-white mb-2"></i>
                    <span class="text-white font-bold text-xs">Atur Meja</span>
                </div>
            <?php endif; ?>

        </div>
    <?php endfor; ?>
</div>

<script>
    function showTableOptions(tableNumber) {
        showConfirm(
            'Atur Meja ' + tableNumber, 
            'Pelanggan sudah pulang? Kosongkan meja ini agar bisa dipakai pelanggan baru.', 
            () => {
                clearTable(tableNumber);
            }
        );
        
        document.getElementById('btn-confirm-yes').innerText = "Kosongkan Meja";
        document.getElementById('btn-confirm-yes').classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        document.getElementById('btn-confirm-yes').classList.add('bg-red-600', 'hover:bg-red-700');
    }

    function clearTable(tableNumber) {
        const formData = new FormData();
        formData.append('table_number', tableNumber);

        fetch('<?php echo site_url("dashboard/clear_table_action"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                showPopup('Berhasil', 'Meja ' + tableNumber + ' sekarang KOSONG dan siap dipakai.', 'success', () => location.reload());
            } else {
                showPopup('Gagal', 'Gagal mengupdate status meja.', 'error');
            }
        })
        .catch(err => showPopup('Error', 'Koneksi server bermasalah.', 'error'));
    }

    // [UPDATED] Fungsi Tambah/Kurang Meja
    function modifyTable(action) {
        const endpoint = action === 'add' ? 'add_table_action' : 'reduce_table_action';
        // Pesan konfirmasi yang beda untuk hapus
        const confirmMsg = action === 'add' 
            ? 'Tambah 1 meja baru ke dalam denah?' 
            : 'Hapus meja terakhir? Pastikan meja tersebut sedang kosong.';

        showConfirm('Ubah Jumlah Meja', confirmMsg, () => {
            fetch('<?php echo site_url("dashboard/"); ?>' + endpoint)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    showPopup('Berhasil', 'Jumlah meja diperbarui.', 'success', () => location.reload());
                } else {
                    // Tampilkan pesan error spesifik (misal: Meja terisi)
                    showPopup('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });
        
        // Reset tombol confirm jadi warna standar
        document.getElementById('btn-confirm-yes').innerText = "Ya, Lakukan";
        document.getElementById('btn-confirm-yes').classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        document.getElementById('btn-confirm-yes').classList.remove('bg-red-600', 'hover:bg-red-700');
    }
</script>