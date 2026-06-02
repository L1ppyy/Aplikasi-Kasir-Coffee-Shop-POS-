<?php $__env->startSection('title', 'Produk'); ?>
<?php $__env->startSection('page-title', 'Manajemen Produk'); ?>
<?php $__env->startSection('page-subtitle', 'CRUD produk, stok, dan harga'); ?>

<?php $__env->startSection('topbar-actions'); ?>
<a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">➕ Tambah Produk</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <input class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="🔍 Cari nama, SKU, barcode..." style="max-width:280px;">
            <select class="form-control" name="category_id" style="max-width:180px;">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select class="form-control" name="status" style="max-width:180px;">
                <option value="">Semua Status</option>
                <option value="active" <?php echo e(request('status')=='active'?'selected':''); ?>>Aktif</option>
                <option value="inactive" <?php echo e(request('status')=='inactive'?'selected':''); ?>>Nonaktif</option>
                <option value="low_stock" <?php echo e(request('status')=='low_stock'?'selected':''); ?>>Stok Menipis</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📦 Daftar Produk <span style="background:#ede9fe;color:#6366f1;padding:2px 10px;border-radius:20px;font-size:12px;margin-left:8px;"><?php echo e($products->total()); ?></span></h3>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>SKU</th>
                    <th>Harga Jual</th>
                    <th>Harga Beli</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:42px;height:42px;border-radius:10px;background:<?php echo e($product->category->color ?? '#e2e8f0'); ?>22;display:flex;align-items:center;justify-content:center;font-size:20px;border:1px solid <?php echo e($product->category->color ?? '#e2e8f0'); ?>44;">
                                <?php if($product->image): ?>
                                <img src="<?php echo e(asset('storage/'.$product->image)); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                                <?php else: ?>
                                📦
                                <?php endif; ?>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px;"><?php echo e($product->name); ?></div>
                                <div style="font-size:11px;color:#94a3b8;"><?php echo e($product->barcode ?? 'Tanpa barcode'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="background:<?php echo e($product->category->color ?? '#94a3b8'); ?>22;color:<?php echo e($product->category->color ?? '#94a3b8'); ?>;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                            <?php echo e($product->category->name ?? '-'); ?>

                        </span>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:#64748b;"><?php echo e($product->sku); ?></td>
                    <td><strong style="color:#0f172a;">Rp <?php echo e(number_format($product->selling_price, 0, ',', '.')); ?></strong></td>
                    <td style="color:#64748b;font-size:13px;">Rp <?php echo e(number_format($product->purchase_price, 0, ',', '.')); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-weight:700;color:<?php echo e($product->stock == 0 ? '#ef4444' : ($product->isLowStock() ? '#f59e0b' : '#10b981')); ?>">
                                <?php echo e($product->stock); ?>

                            </span>
                            <span style="font-size:11px;color:#94a3b8;"><?php echo e($product->unit); ?></span>
                            <?php if($product->isLowStock()): ?>
                            <span class="badge badge-warning" style="font-size:9px;">Menipis</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if($product->is_active): ?>
                        <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                        <span class="badge badge-danger">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-secondary btn-sm">✏️ Edit</a>
                            <button onclick="openStockModal(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>', <?php echo e($product->stock); ?>)" class="btn btn-warning btn-sm">📦 Stok</button>
                            <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;">
                    <div style="font-size:40px;margin-bottom:12px;">📦</div>
                    <div>Tidak ada produk ditemukan</div>
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
        <?php echo e($products->links()); ?>

    </div>
</div>

<!-- Stock Modal -->
<div class="modal-overlay" id="stockModal">
    <div class="modal">
        <div class="modal-header">
            <h3>📦 Sesuaikan Stok</h3>
            <button class="modal-close" onclick="closeStockModal()">✕</button>
        </div>
        <form id="stockForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div id="productNameDisplay" style="font-weight:600; color:#0f172a; margin-bottom:16px; font-size:15px;"></div>
                <div style="display:flex;gap:12px;margin-bottom:16px;">
                    <label style="flex:1;display:flex;gap:8px;align-items:center;padding:12px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="type" value="in" checked> <span>➕ Stok Masuk</span>
                    </label>
                    <label style="flex:1;display:flex;gap:8px;align-items:center;padding:12px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="type" value="out"> <span>➖ Stok Keluar</span>
                    </label>
                    <label style="flex:1;display:flex;gap:8px;align-items:center;padding:12px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="type" value="adjustment"> <span>🔄 Set Stok</span>
                    </label>
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Alasan penyesuaian stok..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeStockModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openStockModal(id, name, stock) {
    document.getElementById('productNameDisplay').textContent = name + ' (Stok saat ini: ' + stock + ')';
    document.getElementById('stockForm').action = '/admin/products/' + id + '/stock';
    document.getElementById('stockModal').classList.add('open');
}
function closeStockModal() {
    document.getElementById('stockModal').classList.remove('open');
}
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) closeStockModal();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/products/index.blade.php ENDPATH**/ ?>