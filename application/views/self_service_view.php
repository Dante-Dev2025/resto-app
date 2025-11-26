<!-- 1. DATA PHP KE JAVASCRIPT -->
<script>
    const menuData = <?php echo json_encode($menu_makanan); ?>;
    // Data meja terisi (akan di-fetch via AJAX nanti, tapi inisialisasi dulu)
    let occupiedTables = []; 
</script>

<div class="flex flex-col-reverse lg:flex-row h-[calc(100vh-130px)] gap-6 overflow-hidden">
    <!-- SIDEBAR -->
    <div class="w-full lg:w-96 bg-white flex flex-col border border-gray-200 shadow-xl rounded-2xl overflow-hidden z-20 h-full">
        <div class="p-4 bg-indigo-600 text-white shadow-md flex justify-between items-center flex-shrink-0">
            <h2 class="font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-receipt"></i> Pesanan Anda</h2>
            <span class="text-xs bg-white/20 px-2 py-1 rounded text-white font-medium">Self Service</span>
        </div>
        <div id="cart-container" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 scrollbar-thin">
            <div id="empty-cart-msg" class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60 mt-10">
                <i class="fa-solid fa-basket-shopping text-6xl mb-3"></i>
                <p class="text-sm font-medium">Keranjang kosong</p>
            </div>
        </div>
        <div class="bg-white p-5 border-t border-gray-200 shadow-[0_-5px_20px_rgba(0,0,0,0.05)] flex-shrink-0">
            <div class="mb-4">
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block tracking-wide">Catatan Dapur</label>
                <div class="relative"><i class="fa-solid fa-pen absolute left-3 top-2.5 text-gray-400 text-xs"></i><input type="text" id="kitchen-note" class="w-full text-sm pl-8 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none" placeholder="Contoh: Jangan pedas..."></div>
            </div>
            <div class="flex items-center justify-between mb-4 pt-3 border-t border-dashed border-gray-200">
                <span class="text-gray-500 font-medium">Total Bayar</span>
                <span class="text-2xl font-bold text-indigo-700" id="total-price">Rp 0</span>
            </div>
            <button onclick="openInputModal()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 transition transform active:scale-[0.98] flex justify-center items-center gap-2 group">
                <span>Pilih Meja & Bayar</span><i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
            </button>
        </div>
    </div>
    <!-- GRID MENU -->
    <div class="flex-1 h-full overflow-y-auto pr-2 pb-20 scroll-smooth scrollbar-hide">
        <div class="mb-6 sticky top-0 bg-gray-50 pt-2 pb-4 z-30"><h1 class="text-2xl font-bold text-gray-800">Menu Restoran</h1></div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach($menu_makanan as $menu): ?>
            <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-lg transition duration-300 flex flex-col h-full group relative overflow-hidden">
                <?php if($menu->stock <= 0): ?><div class="absolute inset-0 bg-white/80 z-10 flex flex-col items-center justify-center text-gray-500"><i class="fa-solid fa-ban text-4xl mb-2"></i><span class="font-bold text-lg">Habis</span></div><?php endif; ?>
                <div class="h-40 w-full rounded-xl overflow-hidden mb-3 bg-gray-100 relative">
                    <img src="<?php echo $menu->image_url; ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    <div class="absolute bottom-2 left-2 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-sm font-bold text-gray-800 shadow-sm">Rp <?php echo number_format($menu->price, 0, ',', '.'); ?></div>
                </div>
                <div class="flex-1 px-1"><h3 class="font-bold text-gray-800 text-lg leading-tight mb-1"><?php echo $menu->name; ?></h3></div>
                <div class="mt-4 flex items-center justify-between bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                    <button onclick="updateQty(<?php echo $menu->id; ?>, -1)" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 transition"><i class="fa-solid fa-minus text-xs"></i></button>
                    <span id="qty-display-<?php echo $menu->id; ?>" class="font-bold text-gray-800 text-lg w-8 text-center">0</span>
                    <button onclick="updateQty(<?php echo $menu->id; ?>, 1)" class="w-9 h-9 flex items-center justify-center bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition <?php echo $menu->stock <= 0 ? 'opacity-50 cursor-not-allowed' : ''; ?>" <?php echo $menu->stock <= 0 ? 'disabled' : ''; ?>><i class="fa-solid fa-plus text-xs"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="h-32 lg:hidden"></div>
    </div>
</div>

<!-- MODAL 1: PILIH MEJA (VISUAL GRID) & METODE -->
<div id="inputModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeInputModal()"></div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center flex-shrink-0">
                <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fa-solid fa-utensils"></i> Detail Pemesanan</h3>
                <button onclick="closeInputModal()" class="text-indigo-200 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <!-- Total Tagihan -->
                <div class="text-center mb-6">
                    <p class="text-gray-500 text-xs uppercase font-bold tracking-wide">Total Tagihan</p>
                    <h2 class="text-4xl font-bold text-indigo-600 mt-1" id="modal-total-display">Rp 0</h2>
                </div>

                <!-- TIPE PESANAN (DINE IN / TAKEAWAY) -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Jenis Pesanan:</label>
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <button id="btn-dine-in" onclick="setOrderType('dine_in')" class="flex-1 py-2 rounded-lg text-sm font-bold text-indigo-600 bg-white shadow-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-utensils"></i> Makan di Tempat
                        </button>
                        <button id="btn-takeaway" onclick="setOrderType('takeaway')" class="flex-1 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-gray-700 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bag-shopping"></i> Bawa Pulang
                        </button>
                    </div>
                    <input type="hidden" id="order-type" value="dine_in">
                </div>

                <!-- GRID MEJA VISUAL (Hanya Muncul jika Dine In) -->
                <div id="table-selection-container" class="mb-6 transition-all duration-300">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Meja:</label>
                    
                    <!-- Legend Status -->
                    <div class="flex gap-4 mb-3 text-xs text-gray-500 justify-center">
                        <div class="flex items-center gap-1"><div class="w-3 h-3 bg-white border border-gray-300 rounded"></div> Kosong</div>
                        <div class="flex items-center gap-1"><div class="w-3 h-3 bg-indigo-600 rounded"></div> Dipilih</div>
                        <div class="flex items-center gap-1"><div class="w-3 h-3 bg-gray-200 border border-gray-300 rounded opacity-50"></div> Terisi</div>
                    </div>

                    <!-- Container Grid Meja -->
                    <div id="tables-grid" class="grid grid-cols-4 sm:grid-cols-5 gap-3 max-h-48 overflow-y-auto p-1">
                        <div class="col-span-full text-center py-4 text-gray-400 italic">Memuat data meja...</div>
                    </div>
                    
                    <p class="text-xs text-red-500 mt-2 hidden text-center font-bold" id="table-error-msg"></p>
                </div>

                <!-- Pesan Takeaway (Muncul jika Takeaway) -->
                <div id="takeaway-msg-container" class="mb-6 hidden p-4 bg-orange-50 border border-orange-100 rounded-xl text-center">
                    <i class="fa-solid fa-bag-shopping text-3xl text-orange-400 mb-2"></i>
                    <p class="text-orange-800 font-bold text-sm">Pesanan Bawa Pulang (Takeaway)</p>
                    <p class="text-xs text-orange-600 mt-1">Silakan tunggu pesanan Anda dipanggil di kasir.</p>
                </div>
                
                <!-- Input Hidden untuk menyimpan nomor meja yang dipilih -->
                <input type="hidden" id="selected-table-number" value="">

                <!-- Metode Pembayaran -->
                <div class="space-y-3 mb-6">
                    <p class="text-xs font-bold text-gray-500 uppercase">Metode Pembayaran</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition group" onclick="selectMethod('QRIS')">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="QRIS" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" checked>
                                <div class="text-sm font-bold text-gray-800">QRIS / E-Wallet</div>
                            </div>
                            <i class="fa-solid fa-qrcode text-xl text-gray-400 group-hover:text-indigo-600"></i>
                        </label>
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition group" onclick="selectMethod('Cash')">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="Cash" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <div class="text-sm font-bold text-gray-800">Tunai / Kasir</div>
                            </div>
                            <i class="fa-solid fa-money-bill-wave text-xl text-gray-400 group-hover:text-indigo-600"></i>
                        </label>
                    </div>
                </div>

                <button onclick="validateAndShowPayment()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 transition transform active:scale-[0.98] flex justify-center items-center gap-2">
                    <span>Lanjut Pembayaran</span><i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: DETAIL PEMBAYARAN (QR/INFO) -->
<div id="paymentDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-sm w-full p-6 relative">
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            <div id="payment-content" class="flex flex-col items-center pt-2"></div>
            <div class="mt-6">
                <button onclick="finalProcessCheckout()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-md transition transform active:scale-[0.98]">Saya Sudah Bayar</button>
                <p class="text-center text-xs text-gray-400 mt-3">Pesanan akan diproses setelah diklik.</p>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = {}; 
    let selectedMethod = 'QRIS'; 
    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

    // --- FUNGSI CART (SAMA) ---
    function updateQty(id, change) {
        const menu = menuData.find(m => m.id == id);
        if (!menu) return;
        if (!cart[id]) cart[id] = 0;
        if (change > 0 && cart[id] >= parseInt(menu.stock)) { showPopup('Stok Terbatas', `Stok ${menu.name} sisa ${menu.stock}.`, 'error'); return; }
        cart[id] += change;
        if (cart[id] < 0) cart[id] = 0;
        const display = document.getElementById(`qty-display-${id}`);
        if(display) display.innerText = cart[id];
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        let total = 0; let html = '';
        menuData.forEach(menu => {
            const qty = cart[menu.id] || 0;
            if (qty > 0) {
                const subtotal = qty * parseInt(menu.price);
                total += subtotal;
                html += `<div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-3 relative"><div class="flex justify-between items-start mb-3"><div><h4 class="font-bold text-gray-800 text-sm">${menu.name}</h4><p class="text-xs text-gray-400 mt-1">@ ${formatter.format(menu.price)}</p></div><span class="font-bold text-indigo-600 text-sm">${formatter.format(subtotal)}</span></div><div class="flex items-center justify-between bg-gray-50 rounded-lg p-1.5 border border-gray-100"><button onclick="updateQty(${menu.id}, -1)" class="w-7 h-7 flex items-center justify-center bg-white border border-gray-200 rounded-md text-gray-500"><i class="fa-solid fa-minus text-xs"></i></button><span class="text-sm font-bold text-gray-800">${qty}x</span><button onclick="updateQty(${menu.id}, 1)" class="w-7 h-7 flex items-center justify-center bg-indigo-100 border border-indigo-200 rounded-md text-indigo-600"><i class="fa-solid fa-plus text-xs"></i></button></div></div>`;
            }
        });
        if (total === 0) html = `<div id="empty-cart-msg" class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60 mt-10"><i class="fa-solid fa-basket-shopping text-6xl mb-3"></i><p class="text-sm font-medium">Keranjang kosong</p></div>`;
        container.innerHTML = html;
        document.getElementById('total-price').innerText = formatter.format(total);
    }

    // --- LOGIC TIPE PESANAN (BARU) ---
    function setOrderType(type) {
        document.getElementById('order-type').value = type;
        
        const btnDine = document.getElementById('btn-dine-in');
        const btnTake = document.getElementById('btn-takeaway');
        const tableContainer = document.getElementById('table-selection-container');
        const takeawayMsg = document.getElementById('takeaway-msg-container');

        if (type === 'dine_in') {
            // Style Active Dine In
            btnDine.className = "flex-1 py-2 rounded-lg text-sm font-bold text-indigo-600 bg-white shadow-sm transition flex items-center justify-center gap-2";
            btnTake.className = "flex-1 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-gray-700 transition flex items-center justify-center gap-2";
            
            // Show Table, Hide Msg
            tableContainer.classList.remove('hidden');
            takeawayMsg.classList.add('hidden');
            
            // Reset selection if needed, or keep it
            document.getElementById('selected-table-number').value = "";
            generateTableGrid(); // Refresh status meja
        } else {
            // Style Active Takeaway
            btnTake.className = "flex-1 py-2 rounded-lg text-sm font-bold text-indigo-600 bg-white shadow-sm transition flex items-center justify-center gap-2";
            btnDine.className = "flex-1 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-gray-700 transition flex items-center justify-center gap-2";
            
            // Hide Table, Show Msg
            tableContainer.classList.add('hidden');
            takeawayMsg.classList.remove('hidden');
            
            // Set table value to TAKEAWAY
            document.getElementById('selected-table-number').value = "TAKEAWAY";
            document.getElementById('table-error-msg').classList.add('hidden');
        }
    }

    // --- LOGIC BUKA MODAL & FETCH MEJA ---
    function openInputModal() {
        const totalStr = document.getElementById('total-price').innerText;
        if(totalStr === 'Rp 0' || totalStr === 'IDR 0') { showPopup('Keranjang Kosong', 'Pilih menu dulu!', 'error'); return; }
        document.getElementById('modal-total-display').innerText = totalStr;
        
        // Default: Dine In
        setOrderType('dine_in');
        
        document.getElementById('inputModal').classList.remove('hidden');
    }

    async function generateTableGrid() {
        const gridContainer = document.getElementById('tables-grid');
        gridContainer.innerHTML = '';
        
        const totalTables = 20; 
        
        const promises = [];
        for (let i = 1; i <= totalTables; i++) {
            const formData = new FormData();
            formData.append('table_number', i);
            promises.push(
                fetch('<?php echo site_url("dashboard/check_table_status"); ?>', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => ({ table: i, occupied: data.occupied }))
            );
        }

        try {
            const results = await Promise.all(promises);
            results.forEach(info => {
                const btn = document.createElement('button');
                btn.className = `
                    p-3 rounded-xl border text-sm font-bold transition flex flex-col items-center gap-1 relative
                    ${info.occupied 
                        ? 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed' // Style Terisi (Abu-abu)
                        : 'bg-white border-gray-200 text-gray-700 hover:border-indigo-500 hover:shadow-md hover:text-indigo-600' // Style Kosong
                    }
                `;
                
                if (info.occupied) {
                    btn.disabled = true;
                    btn.innerHTML = `<i class="fa-solid fa-ban text-xs"></i><span>${info.table}</span>`;
                } else {
                    btn.onclick = () => selectTable(info.table, btn);
                    btn.innerHTML = `<i class="fa-solid fa-chair text-lg mb-1 opacity-50"></i><span>${info.table}</span>`;
                }
                
                btn.id = `table-btn-${info.table}`;
                gridContainer.appendChild(btn);
            });
        } catch (err) {
            console.error(err);
            gridContainer.innerHTML = '<div class="col-span-full text-red-500 text-center text-xs">Gagal memuat data meja.</div>';
        }
    }

    function selectTable(number, btnElement) {
        const allBtns = document.querySelectorAll('[id^="table-btn-"]');
        allBtns.forEach(b => {
            if (!b.disabled) {
                b.className = "p-3 rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-indigo-500 hover:shadow-md hover:text-indigo-600 transition flex flex-col items-center gap-1 text-sm font-bold";
            }
        });
        btnElement.className = "p-3 rounded-xl border-2 border-indigo-600 bg-indigo-50 text-indigo-700 shadow-md flex flex-col items-center gap-1 text-sm font-bold transform scale-105 transition";
        document.getElementById('selected-table-number').value = number;
        document.getElementById('table-error-msg').classList.add('hidden');
    }

    function closeInputModal() { document.getElementById('inputModal').classList.add('hidden'); }
    function selectMethod(method) { selectedMethod = method; }

    function validateAndShowPayment() {
        const tableNo = document.getElementById('selected-table-number').value;
        const errorMsg = document.getElementById('table-error-msg');
        
        if (!tableNo) { 
            errorMsg.innerText = "Silakan pilih meja terlebih dahulu!"; 
            errorMsg.classList.remove('hidden'); 
            return; 
        }
        
        showPaymentDetail();
    }

    function showPaymentDetail() {
        closeInputModal();
        const totalStr = document.getElementById('total-price').innerText;
        const contentDiv = document.getElementById('payment-content');

        if (selectedMethod === 'QRIS') {
            contentDiv.innerHTML = `<h3 class="text-xl font-bold text-gray-800 mb-1">Scan QRIS</h3><p class="text-gray-500 text-sm mb-4">Scan kode di bawah ini</p><div class="bg-white p-2 rounded-xl border border-gray-200 shadow-sm mb-4"><img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BAYAR_${totalStr}" alt="QRIS Code" class="w-48 h-48 mx-auto"></div><p class="text-lg font-bold text-indigo-600">${totalStr}</p><p class="text-xs text-gray-400 mt-2">Gopay / OVO / Dana / ShopeePay</p>`;
        } else {
            contentDiv.innerHTML = `<div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 text-3xl"><i class="fa-solid fa-cash-register"></i></div><h3 class="text-xl font-bold text-gray-800 mb-1">Bayar Tunai</h3><p class="text-gray-500 text-sm mb-6">Silakan menuju kasir untuk membayar.</p><div class="flex justify-between w-full px-4 py-3 bg-indigo-50 rounded-lg text-indigo-700 text-sm font-bold"><span>Total Tagihan</span><span>${totalStr}</span></div>`;
        }
        document.getElementById('paymentDetailModal').classList.remove('hidden');
    }
    
    function closeDetailModal() { document.getElementById('paymentDetailModal').classList.add('hidden'); }

    function finalProcessCheckout() {
        const note = document.getElementById('kitchen-note').value;
        const tableNo = document.getElementById('selected-table-number').value;
        
        let itemsToSend = [];
        let totalPrice = 0;
        menuData.forEach(menu => {
            const qty = cart[menu.id] || 0;
            if (qty > 0) { itemsToSend.push({ id: menu.id, name: menu.name, price: parseInt(menu.price), qty: qty }); totalPrice += (qty * parseInt(menu.price)); }
        });
        const payload = { items: itemsToSend, total_price: totalPrice, payment_method: selectedMethod, note: note, table_number: tableNo };
        
        fetch('<?php echo site_url("dashboard/process_checkout"); ?>', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
        .then(response => response.json()).then(data => {
            if(data.status === 'success') { 
                closeDetailModal();
                showPopup('Pesanan Berhasil!', `Nomor Order: ${data.order_number}. ${tableNo === 'TAKEAWAY' ? 'Mohon tunggu di area pengambilan.' : 'Mohon tunggu di meja ' + tableNo + '.'}`, 'success', () => location.reload());
            } else { 
                showPopup('Gagal', data.message, 'error'); 
            }
        });
    }
</script>
<style>.scrollbar-hide::-webkit-scrollbar { display: none; } .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }</style>