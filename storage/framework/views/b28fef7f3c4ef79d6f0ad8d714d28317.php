<?php $__env->startSection('title','Pengaturan'); ?>
<?php $__env->startSection('page-title','Pengaturan Sistem'); ?>
<?php $__env->startSection('page-subtitle','Konfigurasi toko dan aplikasi POS'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:700px;">
<form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" enctype="multipart/form-data">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

  <div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>🏪 Informasi Toko</h3></div>
    <div class="card-body">
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label">Nama Toko *</label>
          <input type="text" name="store_name" class="form-control" value="<?php echo e($settings['store_name'] ?? ''); ?>" required>
        </div>
        <div class="form-group full">
          <label class="form-label">Alamat Toko</label>
          <textarea name="store_address" class="form-control" rows="2"><?php echo e($settings['store_address'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Telepon</label>
          <input type="text" name="store_phone" class="form-control" value="<?php echo e($settings['store_phone'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Email Toko</label>
          <input type="email" name="store_email" class="form-control" value="<?php echo e($settings['store_email'] ?? ''); ?>">
        </div>
        <div class="form-group full">
          <label class="form-label">Logo Toko</label>
          <?php if(!empty($settings['logo'])): ?>
          <img src="<?php echo e(asset('storage/'.$settings['logo'])); ?>" style="width:80px;height:80px;object-fit:cover;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:8px;display:block;">
          <?php endif; ?>
          <input type="file" name="logo" accept="image/*" class="form-control">
        </div>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>💰 Konfigurasi Transaksi</h3></div>
    <div class="card-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Pajak Default (%)</label>
          <input type="number" name="tax_percent" class="form-control" value="<?php echo e($settings['tax_percent'] ?? '0'); ?>" min="0" max="100" step="0.5">
          <small style="color:#94a3b8;font-size:12px;">Isi 0 jika tidak ada pajak</small>
        </div>
        <div class="form-group">
          <label class="form-label">Mata Uang</label>
          <select name="currency" class="form-control">
            <option value="IDR" <?php echo e(($settings['currency']??'IDR')=='IDR'?'selected':''); ?>>IDR - Rupiah</option>
            <option value="USD" <?php echo e(($settings['currency']??'IDR')=='USD'?'selected':''); ?>>USD - Dollar</option>
            <option value="MYR" <?php echo e(($settings['currency']??'IDR')=='MYR'?'selected':''); ?>>MYR - Ringgit</option>
          </select>
        </div>
        <div class="form-group full">
          <label class="form-label">Pesan Footer Struk</label>
          <input type="text" name="receipt_footer" class="form-control" value="<?php echo e($settings['receipt_footer'] ?? ''); ?>" placeholder="Contoh: Terima kasih atas kunjungan anda!">
        </div>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary" style="padding:12px 32px;">✅ Simpan Pengaturan</button>
</form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>