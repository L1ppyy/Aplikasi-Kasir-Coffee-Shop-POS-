<?php $__env->startSection('title','Transaksi'); ?>
<?php $__env->startSection('page-title','Data Transaksi'); ?>
<?php $__env->startSection('page-subtitle','Riwayat seluruh transaksi penjualan'); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:20px;">
  <div class="card-body" style="padding:16px 20px;">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
      <input class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="🔍 No. invoice / nama pelanggan..." style="max-width:260px;">
      <input type="date" class="form-control" name="date_from" value="<?php echo e(request('date_from')); ?>" style="max-width:160px;">
      <input type="date" class="form-control" name="date_to" value="<?php echo e(request('date_to')); ?>" style="max-width:160px;">
      <select class="form-control" name="status" style="max-width:160px;">
        <option value="">Semua Status</option>
        <option value="completed"  <?php echo e(request('status')=='completed'?'selected':''); ?>>Selesai</option>
        <option value="pending"    <?php echo e(request('status')=='pending'?'selected':''); ?>>Pending</option>
        <option value="cancelled"  <?php echo e(request('status')=='cancelled'?'selected':''); ?>>Dibatalkan</option>
        <option value="refunded"   <?php echo e(request('status')=='refunded'?'selected':''); ?>>Refund</option>
      </select>
      <select class="form-control" name="payment_method" style="max-width:160px;">
        <option value="">Semua Pembayaran</option>
        <option value="cash"     <?php echo e(request('payment_method')=='cash'?'selected':''); ?>>Tunai</option>
        <option value="debit"    <?php echo e(request('payment_method')=='debit'?'selected':''); ?>>Debit</option>
        <option value="qris"     <?php echo e(request('payment_method')=='qris'?'selected':''); ?>>QRIS</option>
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-secondary">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3>🧾 Daftar Transaksi <span style="background:#ede9fe;color:#6366f1;padding:2px 10px;border-radius:20px;font-size:12px;margin-left:8px;"><?php echo e($transactions->total()); ?></span></h3>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Invoice</th>
          <th>Kasir</th>
          <th>Pelanggan</th>
          <th>Items</th>
          <th>Total</th>
          <th>Pembayaran</th>
          <th>Status</th>
          <th>Waktu</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td><a href="<?php echo e(route('admin.transactions.show',$trx)); ?>" style="color:#6366f1;font-weight:600;text-decoration:none;font-size:13px;"><?php echo e($trx->invoice_number); ?></a></td>
          <td style="font-size:13px;"><?php echo e($trx->user->name ?? '-'); ?></td>
          <td style="font-size:13px;color:#64748b;"><?php echo e($trx->customer_name ?? 'Umum'); ?></td>
          <td><span class="badge badge-purple"><?php echo e($trx->items->count()); ?> item</span></td>
          <td><strong>Rp <?php echo e(number_format($trx->total,0,',','.')); ?></strong></td>
          <td>
            <?php $pm=['cash'=>['Tunai','badge-success'],
                        'qris'=>['QRIS','badge-warning'],
                        'transfer'=>['Transfer','badge-gray']] ?>
            <span class="badge <?php echo e($pm[$trx->payment_method][1] ?? 'badge-gray'); ?>"><?php echo e($pm[$trx->payment_method][0] ?? $trx->payment_method); ?></span>
          </td>
          <td>
            <?php if($trx->status=='completed'): ?>    <span class="badge badge-success">Selesai</span>
            <?php elseif($trx->status=='cancelled'): ?> <span class="badge badge-danger">Dibatalkan</span>
            <?php elseif($trx->status=='refunded'): ?>  <span class="badge badge-warning">Refund</span>
            <?php else: ?>                              <span class="badge badge-gray">Pending</span>
            <?php endif; ?>
          </td>
          <td style="font-size:12px;color:#94a3b8;"><?php echo e($trx->created_at->format('d/m/Y H:i')); ?></td>
          <td>
            <a href="<?php echo e(route('admin.transactions.show',$trx)); ?>" class="btn btn-secondary btn-sm">👁️ Detail</a>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="9" style="text-align:center;padding:40px;color:#94a3b8;">Tidak ada transaksi</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div style="padding:16px 24px;border-top:1px solid #f1f5f9;"><?php echo e($transactions->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/transactions/index.blade.php ENDPATH**/ ?>