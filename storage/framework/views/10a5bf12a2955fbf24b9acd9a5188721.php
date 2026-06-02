<?php $__env->startSection('title', 'Edit Produk'); ?>
<?php $__env->startSection('page-title', 'Edit Produk'); ?>
<?php $__env->startSection('page-subtitle', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 800px;">
<form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header"><h3>📋 Informasi Dasar</h3></div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $product->name)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" class="form-control" required>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $product->category_id) == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SKU *</label>
                    <input type="text" name="sku" class="form-control" value="<?php echo e(old('sku', $product->sku)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="<?php echo e(old('barcode', $product->barcode)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan *</label>
                    <select name="unit" class="form-control" required>
                        <?php $__currentLoopData = ['pcs','porsi','gelas','botol','kg','gram','liter','pack','box','strip']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u); ?>" <?php echo e(old('unit', $product->unit) == $u ? 'selected' : ''); ?>><?php echo e($u); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?php echo e($product->is_active ? 'selected' : ''); ?>>Aktif</option>
                        <option value="0" <?php echo e(!$product->is_active ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control"><?php echo e(old('description', $product->description)); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header"><h3>💰 Harga & Stok</h3></div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Harga Beli (HPP)</label>
                    <input type="number" name="purchase_price" class="form-control" value="<?php echo e(old('purchase_price', $product->purchase_price)); ?>" min="0" step="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Jual *</label>
                    <input type="number" name="selling_price" class="form-control" value="<?php echo e(old('selling_price', $product->selling_price)); ?>" min="0" step="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Minimum (Alert)</label>
                    <input type="number" name="min_stock" class="form-control" value="<?php echo e(old('min_stock', $product->min_stock)); ?>" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Saat Ini</label>
                    <input type="text" class="form-control" value="<?php echo e($product->stock); ?> <?php echo e($product->unit); ?>" disabled style="background:#f1f5f9;">
                    <small style="color:#94a3b8;font-size:12px;">Untuk ubah stok, gunakan tombol "Sesuaikan Stok" di halaman daftar produk</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header"><h3>🖼️ Gambar Produk</h3></div>
        <div class="card-body" style="display:flex;gap:20px;align-items:flex-start;">
            <?php if($product->image): ?>
            <img src="<?php echo e(asset('storage/'.$product->image)); ?>" style="width:100px;height:100px;object-fit:cover;border-radius:12px;border:2px solid #e2e8f0;">
            <?php endif; ?>
            <div style="flex:1;">
                <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
                <img id="preview" style="margin-top:12px;max-width:100px;border-radius:12px;display:none;">
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px;">
        <button type="submit" class="btn btn-primary">✅ Simpan Perubahan</button>
        <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">Batal</a>
    </div>
</form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>