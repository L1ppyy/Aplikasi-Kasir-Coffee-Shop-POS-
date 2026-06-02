<?php $__env->startSection('title', 'Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('page-subtitle', 'Kelola admin dan kasir sistem'); ?>

<?php $__env->startSection('topbar-actions'); ?>
<button onclick="openModal('addModal')" class="btn btn-primary">➕ Tambah Pengguna</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header"><h3>👥 Daftar Pengguna</h3></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Pengguna</th><th>Email</th><th>Role</th><th>Telepon</th><th>Transaksi</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#f59e0b);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </div>
                            <div style="font-weight:600;"><?php echo e($user->name); ?></div>
                        </div>
                    </td>
                    <td style="color:#64748b;font-size:13px;"><?php echo e($user->email); ?></td>
                    <td>
                        <?php if($user->role === 'admin'): ?>
                        <span class="badge badge-purple">Admin</span>
                        <?php else: ?>
                        <span class="badge badge-info">Kasir</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#64748b;font-size:13px;"><?php echo e($user->phone ?? '-'); ?></td>
                    <td><span class="badge badge-gray"><?php echo e($user->transactions_count); ?></span></td>
                    <td>
                        <?php if($user->is_active): ?> <span class="badge badge-success">Aktif</span>
                        <?php else: ?> <span class="badge badge-danger">Nonaktif</span> <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button onclick='openEditModal(<?php echo json_encode($user, 15, 512) ?>)' class="btn btn-secondary btn-sm">✏️ Edit</button>
                            <?php if($user->id !== auth()->id()): ?>
                            <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;">Belum ada pengguna</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <h3>➕ Tambah Pengguna</h3>
            <button class="modal-close" onclick="closeModal('addModal')">✕</button>
        </div>
        <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-control">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <h3>✏️ Edit Pengguna</h3>
            <button class="modal-close" onclick="closeModal('editModal')">✕</button>
        </div>
        <form id="editForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="modal-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" id="edit_role" class="form-control">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="edit_active" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openEditModal(user) {
    document.getElementById('editForm').action = '/admin/users/' + user.id;
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_active').value = user.is_active ? '1' : '0';
    document.getElementById('edit_phone').value = user.phone || '';
    openModal('editModal');
}
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/users/index.blade.php ENDPATH**/ ?>