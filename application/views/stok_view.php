<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Summary Cards -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Total Menu</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1"><?php echo isset($total_item) ? $total_item : 0; ?></p>
    </div>
    <div class="bg-red-50 p-4 rounded-xl shadow-sm border border-red-100 flex flex-col">
        <p class="text-red-500 text-xs uppercase tracking-wide font-semibold">Stok Kritis / Habis</p>
        <p class="text-3xl font-bold text-red-600 mt-1"><?php echo isset($stok_kritis) ? $stok_kritis : 0; ?></p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h3 class="font-bold text-gray-800">Laporan Stok Makanan</h3>
        <button onclick="openModal('add')" class="text-xs bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Menu
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Menu</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if(!empty($stok_barang)): ?>
                    <?php foreach($stok_barang as $item): ?>
                    <?php 
                        $current_stock = $item->stock; 
                        $status_label = 'Aman';
                        $status_color = 'bg-green-100 text-green-700 border-green-200';
                        if ($current_stock == 0) {
                            $status_label = 'Habis';
                            $status_color = 'bg-gray-100 text-gray-600 border-gray-200';
                        } elseif ($current_stock <= 10) {
                            $status_label = 'Kritis';
                            $status_color = 'bg-red-100 text-red-700 border-red-200';
                        }
                        $itemJson = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                    <img class="h-10 w-10 object-cover" src="<?php echo $item->image_url; ?>" alt="" onerror="this.src='https://via.placeholder.com/40'">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900"><?php echo $item->name; ?></div>
                                    <div class="text-xs text-gray-500">ID: #PRD-<?php echo $item->id; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 capitalize bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                <?php echo $item->category; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900"><?php echo $item->stock; ?> <span class="text-xs font-normal text-gray-500">Porsi</span></div>
                            <div class="w-24 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                <div class="h-full bg-indigo-500" style="width: <?php echo min($item->stock, 100); ?>%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border <?php echo $status_color; ?>">
                                <?php echo $status_label; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button onclick="openModal('edit', <?php echo $itemJson; ?>)" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-2 rounded transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data menu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM (UPDATE: LEBIH KECIL & CENTER) -->
<div id="menuModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    
    <!-- Overlay Gelap -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

    <!-- Container Flexbox Center -->
    <div class="flex min-h-screen items-center justify-center p-4 text-center">
        
        <!-- Panel Modal (Lebih kecil: max-w-md, Tinggi max 90vh) -->
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all flex flex-col max-h-[90vh]">
            
            <!-- Header (Fixed) -->
            <div class="bg-gray-50 px-5 py-3 border-b border-gray-100 flex justify-between items-center flex-shrink-0">
                <h3 class="text-lg font-bold text-gray-800" id="modal-title">Menu</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Body (Scrollable jika panjang) -->
            <div class="px-5 py-5 overflow-y-auto">
                <div class="space-y-4">
                    <input type="hidden" id="edit_id"> 
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Nama Menu</label>
                        <input type="text" id="edit_name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Nasi Goreng">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Kategori</label>
                            <select id="edit_category" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none bg-white text-sm">
                                <option value="food">Makanan</option>
                                <option value="drink">Minuman</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Harga (Rp)</label>
                            <input type="number" id="edit_price" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none text-sm" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Stok Awal</label>
                        <input type="number" id="edit_stock" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none text-sm" placeholder="0">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">URL Gambar</label>
                        <input type="text" id="edit_image" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none text-xs text-gray-600" placeholder="https://...">
                    </div>

                    <!-- Tombol Hapus -->
                    <div id="delete-container" class="hidden pt-4 border-t border-gray-100 mt-2">
                        <button onclick="deleteMenu()" class="w-full text-red-600 text-xs font-bold hover:bg-red-50 p-2 rounded-lg transition flex items-center justify-center gap-2 border border-red-100">
                            <i class="fa-solid fa-trash"></i> Hapus Menu Ini
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer (Fixed) -->
            <div class="bg-gray-50 px-5 py-3 flex flex-row-reverse gap-2 flex-shrink-0 border-t border-gray-100">
                <button type="button" onclick="saveMenu()" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none transition">
                    Simpan
                </button>
                <button type="button" onclick="closeModal()" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(mode, data = null) {
        const modal = document.getElementById('menuModal');
        modal.classList.remove('hidden');
        
        // Animasi Pop
        const panel = modal.querySelector('.relative');
        panel.classList.remove('scale-95', 'opacity-0');
        panel.classList.add('scale-100', 'opacity-100');

        if (mode === 'edit' && data) {
            document.getElementById('modal-title').innerText = 'Edit Stok & Menu';
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_category').value = data.category;
            document.getElementById('edit_price').value = data.price;
            document.getElementById('edit_stock').value = data.stock;
            document.getElementById('edit_image').value = data.image_url;
            document.getElementById('delete-container').classList.remove('hidden');
        } else {
            document.getElementById('modal-title').innerText = 'Tambah Menu Baru';
            document.getElementById('edit_id').value = '';
            document.getElementById('edit_name').value = '';
            document.getElementById('edit_stock').value = 0;
            document.getElementById('edit_price').value = '';
            document.getElementById('edit_image').value = '';
            document.getElementById('delete-container').classList.add('hidden');
        }
    }

    function closeModal() {
        const modal = document.getElementById('menuModal');
        // Animasi Close
        const panel = modal.querySelector('.relative');
        panel.classList.remove('scale-100', 'opacity-100');
        panel.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150); // Tunggu animasi selesai
    }

    function saveMenu() {
        const formData = new FormData();
        formData.append('id', document.getElementById('edit_id').value);
        formData.append('name', document.getElementById('edit_name').value);
        formData.append('category', document.getElementById('edit_category').value);
        formData.append('price', document.getElementById('edit_price').value);
        formData.append('stock', document.getElementById('edit_stock').value);
        formData.append('image_url', document.getElementById('edit_image').value);

        fetch('<?php echo site_url("dashboard/save_product"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                showPopup('Berhasil', 'Data menu berhasil disimpan!', 'success', () => location.reload());
            } else {
                showPopup('Gagal', data.message || 'Gagal menyimpan.', 'error');
            }
        })
        .catch(error => {
            showPopup('Error', 'Terjadi kesalahan koneksi.', 'error');
        });
    }

    function deleteMenu() {
        showConfirm('Hapus Menu?', 'Yakin ingin menghapus menu ini? Data tidak bisa dikembalikan.', () => {
            const id = document.getElementById('edit_id').value;
            const formData = new FormData();
            formData.append('id', id);

            fetch('<?php echo site_url("dashboard/delete_product"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    showPopup('Terhapus', 'Menu berhasil dihapus!', 'success', () => location.reload());
                } else {
                    showPopup('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                showPopup('Error', 'Terjadi kesalahan koneksi.', 'error');
            });
        });
    }
</script>