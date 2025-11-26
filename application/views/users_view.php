<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen User (Role)</h2>
            <p class="text-gray-500 text-sm">Ubah hak akses pengguna di sini.</p>
        </div>
        <div class="bg-yellow-50 text-yellow-800 px-4 py-2 rounded-lg text-sm border border-yellow-200">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
            Hanya Admin yang bisa mengakses halaman ini.
        </div>
    </div>

    <!-- Tabel User -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Role Saat Ini</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Ubah Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($all_users as $user): ?>
                <tr class="hover:bg-gray-50">
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?php echo $user->name; ?>
                        <?php if($user->id == $this->session->userdata('user_id')): ?>
                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">(Anda)</span>
                        <?php endif; ?>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo $user->email; ?>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <!-- Badge Warna Warni sesuai Role -->
                        <?php 
                            $color = 'bg-gray-100 text-gray-800';
                            if($user->role == 'admin') $color = 'bg-red-100 text-red-800';
                            if($user->role == 'cashier') $color = 'bg-green-100 text-green-800';
                            if($user->role == 'guest') $color = 'bg-blue-100 text-blue-800';
                        ?>
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
                            <?php echo ucfirst($user->role); ?>
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <!-- FORM GANTI ROLE -->
                        <form action="<?php echo site_url('dashboard/change_role'); ?>" method="post" class="flex items-center gap-2">
                            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                            
                            <select name="role" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-1">
                                <option value="guest" <?php echo ($user->role == 'guest' ? 'selected' : ''); ?>>Guest</option>
                                <option value="cashier" <?php echo ($user->role == 'cashier' ? 'selected' : ''); ?>>Cashier</option>
                                <option value="admin" <?php echo ($user->role == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            </select>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs transition">
                                Update
                            </button>
                        </form>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>