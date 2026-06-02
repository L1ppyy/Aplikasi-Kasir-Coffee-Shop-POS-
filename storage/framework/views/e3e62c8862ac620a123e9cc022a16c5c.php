<?php $__env->startSection('title','Pengeluaran'); ?>
<?php $__env->startSection('page-title','Pengeluaran'); ?>
<?php $__env->startSection('page-subtitle','Catat dan kelola biaya operasional'); ?>

<?php $__env->startSection('topbar-actions'); ?>
<button onclick="document.getElementById('addModal').classList.add('open')" class="btn btn-primary">➕ Catat Pengeluaran</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:20px;">
  <div class="card-body" style="padding:16px 20px;">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
      <input type="date" class="form-control" name="date_from" value="<?php echo e(request('date_from')); ?>" style="max-width:160px;">
      <input type="date" class="form-control" name="date_to" value="<?php echo e(request('date_to')); ?>" style="max-width:160px;">
      <select class="form-control" name="category" style="max-width:180px;">
        <option value="">Semua Kategori</option>
        <?php $__currentLoopData = ['operasional','gaji','sewa','utilitas','bahan','lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c); ?>" <?php echo e(request('category')==$c?'selected':''); ?>><?php echo e(ucfirst($c)); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="<?php echo e(route('admin.expenses.index')); ?>" class="btn btn-secondary">Reset</a>
    </form>
  </div>
</div>

<div style="display:flex;gap:16px;margin-bottom:20px;">
  <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;padding:18px 24px;flex:1;border-left:4px solid #ef4444;">
    <h2 style="font-size:22px;font-weight:700;color:#ef4444;">Rp <?php echo e(number_format($total,0,',','.')); ?></h2>
    <p style="font-size:12px;color:#64748b;">Total Pengeluaran Terfilter</p>
  </div>
</div>

<div class="card">
  <div class="card-header"><h3>💸 Daftar Pengeluaran</h3></div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th>Dicatat oleh</th><th>Jumlah</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td style="font-size:13px;"><?php echo e(\Carbon\Carbon::parse($exp->expense_date)->format('d M Y')); ?></td>
          <td>
            <div style="font-weight:600;"><?php echo e($exp->title); ?></div>
            <?php if($exp->description): ?><div style="font-size:11px;color:#94a3b8;"><?php echo e($exp->description); ?></div><?php endif; ?>
          </td>
          <td>
            <?php $catColors=['operasional'=>'#6366f1','gaji'=>'#10b981','sewa'=>'#f59e0b','utilitas'=>'#3b82f6','bahan'=>'#8b5cf6','lainnya'=>'#64748b'] ?>
            <span style="background:<?php echo e(($catColors[$exp->category]??'#64748b')); ?>22;color:<?php echo e($catColors[$exp->category]??'#64748b'); ?>;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;text-transform:capitalize;"><?php echo e($exp->category); ?></span>
          </td>
          <td style="font-size:13px;color:#64748b;"><?php echo e($exp->user->name ?? '-'); ?></td>
          <td><strong style="color:#ef4444;">Rp <?php echo e(number_format($exp->amount,0,',','.')); ?></strong></td>
          <td>
            <form action="<?php echo e(route('admin.expenses.destroy',$exp)); ?>" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?')">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
            </form>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">Belum ada pengeluaran tercatat</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div style="padding:16px 24px;border-top:1px solid #f1f5f9;"><?php echo e($expenses->links()); ?></div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
  <div class="modal">
    <div class="modal-header">
      <h3>➕ Catat Pengeluaran</h3>
      <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('open')">✕</button>
    </div>
    <form action="<?php echo e(route('admin.expenses.store')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <div class="modal-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
          <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Keterangan *</label>
            <input type="text" name="title" class="form-control" required placeholder="Contoh: Bayar listrik bulanan">
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah (Rp) *</label>
            <input type="number" name="amount" class="form-control" min="0" required placeholder="0">
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal *</label>
            <input type="date" name="expense_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
          </div>
          <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Kategori *</label>
            <select name="category" class="form-control" required>
              <?php $__currentLoopData = ['operasional'=>'Operasional','gaji'=>'Gaji Karyawan','sewa'=>'Sewa Tempat','utilitas'=>'Utilitas (Listrik/Air)','bahan'=>'Pembelian Bahan','lainnya'=>'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($v); ?>"><?php echo e($l); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Detail tambahan..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('addModal').classList.remove('open')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.getElementById('addModal').addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/expenses/index.blade.php ENDPATH**/ ?>