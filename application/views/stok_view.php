<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
        <button onclick="openModal('add')" class="text-xs bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition flex items-center gap-2"><i class="fa-solid fa-plus"></i> Tambah Menu</button>
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
                        if ($current_stock == 0) { $status_label = 'Habis'; $status_color = 'bg-gray-100 text-gray-600 border-gray-200'; } 
                        elseif ($current_stock <= 10) { $status_label = 'Kritis'; $status_color = 'bg-red-100 text-red-700 border-red-200'; }
                        $itemJson = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap"><div class="flex items-center"><div class="h-10 w-10 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100"><img class="h-10 w-10 object-cover" src="<?php echo $item->image_url; ?>" onerror="this.src='https://via.placeholder.com/40'"></div><div class="ml-4"><div class="text-sm font-bold text-gray-900"><?php echo $item->name; ?></div><div class="text-xs text-gray-500">ID: #PRD-<?php echo $item->id; ?></div></div></div></td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm text-gray-600 capitalize bg-gray-100 px-2 py-1 rounded border border-gray-200"><?php echo $item->category; ?></span></td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-bold text-gray-900"><?php echo $item->stock; ?> <span class="text-xs font-normal text-gray-500">Porsi</span></div><div class="w-24 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden"><div class="h-full bg-indigo-500" style="width: <?php echo min($item->stock, 100); ?>%"></div></div></td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border <?php echo $status_color; ?>"><?php echo $status_label; ?></span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"><button onclick="openModal('edit', <?php echo $itemJson; ?>)" class="text-indigo-600 hover:text-indigo-900"><i class="fa-solid fa-pen-to-square"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data menu.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM -->
<div id="menuModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Menu</h3>
                <div class="space-y-4">
                    <input type="hidden" id="edit_id"> 
                    <div><label class="block text-sm font-medium text-gray-700">Nama Menu</label><input type="text" id="edit_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700">Kategori</label><select id="edit_category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"><option value="food">Makanan</option><option value="drink">Minuman</option></select></div>
                        <div><label class="block text-sm font-medium text-gray-700">Harga (Rp)</label><input type="number" id="edit_price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700">Stok</label><input type="number" id="edit_stock" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"></div>
                    <div><label class="block text-sm font-medium text-gray-700">URL Gambar</label><input type="text" id="edit_image" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"></div>
                    <div id="delete-container" class="hidden pt-2 border-t mt-4"><button onclick="deleteMenu()" class="text-red-600 text-sm font-bold hover:underline">Hapus Menu Ini</button></div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveMenu()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(mode, data = null) {
        document.getElementById('menuModal').classList.remove('hidden');
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
    function closeModal() { document.getElementById('menuModal').classList.add('hidden'); }

    function saveMenu() {
        const formData = new FormData();
        formData.append('id', document.getElementById('edit_id').value);
        formData.append('name', document.getElementById('edit_name').value);
        formData.append('category', document.getElementById('edit_category').value);
        formData.append('price', document.getElementById('edit_price').value);
        formData.append('stock', document.getElementById('edit_stock').value);
        formData.append('image_url', document.getElementById('edit_image').value);

        fetch('<?php echo site_url("dashboard/save_product"); ?>', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') showPopup('Berhasil', 'Data menu berhasil disimpan!', 'success', () => location.reload());
            else showPopup('Gagal', data.message, 'error');
        });
    }

    function deleteMenu() {
        showConfirm('Hapus Menu?', 'Data yang dihapus tidak bisa dikembalikan.', () => {
            const formData = new FormData();
            formData.append('id', document.getElementById('edit_id').value);
            fetch('<?php echo site_url("dashboard/delete_product"); ?>', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') showPopup('Terhapus', 'Menu berhasil dihapus.', 'success', () => location.reload());
                else showPopup('Gagal', 'Terjadi kesalahan.', 'error');
            });
        });
    }
</script>