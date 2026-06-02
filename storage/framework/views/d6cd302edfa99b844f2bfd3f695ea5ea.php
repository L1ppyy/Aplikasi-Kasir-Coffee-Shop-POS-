<?php $__env->startSection('title', 'Kategori'); ?>
<?php $__env->startSection('page-title', 'Manajemen Kategori'); ?>
<?php $__env->startSection('page-subtitle', 'Kelola kategori produk'); ?>

<?php $__env->startSection('topbar-actions'); ?>
<button onclick="openModal('addModal')" class="btn btn-primary">➕ Tambah Kategori</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>🏷️ Daftar Kategori</h3>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Warna</th>
                    <th>Jumlah Produk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:<?php echo e($cat->color); ?>22;display:flex;align-items:center;justify-content:center;font-size:20px;">
                                <?php echo e($cat->icon ?? '🏷️'); ?>

                            </div>
                            <div>
                                <div style="font-weight:600;"><?php echo e($cat->name); ?></div>
                                <div style="font-size:11px;color:#94a3b8;"><?php echo e($cat->description ?? 'Tanpa deskripsi'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:20px;height:20px;border-radius:4px;background:<?php echo e($cat->color); ?>;"></div>
                            <span style="font-family:monospace;font-size:12px;"><?php echo e($cat->color); ?></span>
                        </div>
                    </td>
                    <td><span class="badge badge-purple"><?php echo e($cat->products_count); ?> produk</span></td>
                    <td>
                        <?php if($cat->is_active): ?> <span class="badge badge-success">Aktif</span>
                        <?php else: ?> <span class="badge badge-danger">Nonaktif</span> <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button onclick='openEditModal(<?php echo json_encode($cat, 15, 512) ?>)' class="btn btn-secondary btn-sm">✏️ Edit</button>
                            <form action="<?php echo e(route('admin.categories.destroy', $cat)); ?>" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;">Belum ada kategori</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <h3>➕ Tambah Kategori</h3>
            <button class="modal-close" onclick="closeModal('addModal')">✕</button>
        </div>
        <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: Makanan">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Warna</label>
                    <input type="color" name="color" class="form-control" value="#6366f1" style="height:44px;cursor:pointer;">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Icon (Emoji)</label>
                    <input type="text" name="icon" class="form-control" placeholder="🏷️" maxlength="4">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
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
    <div class="modal">
        <div class="modal-header">
            <h3>✏️ Edit Kategori</h3>
            <button class="modal-close" onclick="closeModal('editModal')">✕</button>
        </div>
        <form id="editForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Warna</label>
                    <input type="color" name="color" id="edit_color" class="form-control" style="height:44px;cursor:pointer;">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Icon (Emoji)</label>
                    <input type="text" name="icon" id="edit_icon" class="form-control" maxlength="4">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="edit_desc" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" id="edit_status" class="form-control">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
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
function openEditModal(cat) {
    document.getElementById('editForm').action = '/admin/categories/' + cat.id;
    document.getElementById('edit_name').value = cat.name;
    document.getElementById('edit_color').value = cat.color;
    document.getElementById('edit_icon').value = cat.icon;
    document.getElementById('edit_desc').value = cat.description || '';
    document.getElementById('edit_status').value = cat.is_active ? '1' : '0';
    openModal('editModal');
}
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>