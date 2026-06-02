<?php $__env->startSection('title','Detail Transaksi'); ?>
<?php $__env->startSection('page-title','Detail Transaksi'); ?>
<?php $__env->startSection('page-subtitle', $transaction->invoice_number); ?>

<?php $__env->startSection('topbar-actions'); ?>
<a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-secondary">← Kembali</a>
<button onclick="window.print()" class="btn btn-primary">🖨️ Cetak</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
@media print {
  .sidebar,.topbar,.topbar-actions,.btn,.alert { display:none!important; }
  .main-content { margin-left:0!important; }
  .page-content { padding:0!important; }
}
.receipt-box { max-width:380px; margin:0 auto; font-family:'Courier New',monospace; border:2px dashed #e2e8f0; border-radius:16px; padding:24px; }
.receipt-divider { border:none; border-top:1px dashed #e2e8f0; margin:12px 0; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;">

  <!-- Left: Detail -->
  <div>
    <div class="card" style="margin-bottom:20px;">
      <div class="card-header" style="justify-content:space-between;">
        <h3>📋 Informasi Transaksi</h3>
        <div style="display:flex;gap:8px;align-items:center;">
          <?php if($transaction->status=='completed'): ?>    <span class="badge badge-success" style="font-size:13px;padding:5px 14px;">✅ Selesai</span>
          <?php elseif($transaction->status=='cancelled'): ?> <span class="badge badge-danger" style="font-size:13px;padding:5px 14px;">❌ Dibatalkan</span>
          <?php elseif($transaction->status=='refunded'): ?>  <span class="badge badge-warning" style="font-size:13px;padding:5px 14px;">↩️ Refund</span>
          <?php else: ?>                                      <span class="badge badge-gray" style="font-size:13px;padding:5px 14px;">⏳ Pending</span>
          <?php endif; ?>

          <form action="<?php echo e(route('admin.transactions.status',$transaction)); ?>" method="POST" style="display:flex;gap:6px;">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <select name="status" class="form-control" style="max-width:150px;">
              <option value="completed"  <?php echo e($transaction->status=='completed'?'selected':''); ?>>Selesai</option>
              <option value="pending"    <?php echo e($transaction->status=='pending'?'selected':''); ?>>Pending</option>
              <option value="cancelled"  <?php echo e($transaction->status=='cancelled'?'selected':''); ?>>Dibatalkan</option>
              <option value="refunded"   <?php echo e($transaction->status=='refunded'?'selected':''); ?>>Refund</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">No. Invoice</p>
            <p style="font-weight:700;color:#6366f1;font-size:16px;"><?php echo e($transaction->invoice_number); ?></p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal & Waktu</p>
            <p style="font-weight:600;"><?php echo e($transaction->created_at->format('d F Y, H:i')); ?></p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kasir</p>
            <p style="font-weight:600;"><?php echo e($transaction->user->name ?? '-'); ?></p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Pelanggan</p>
            <p style="font-weight:600;"><?php echo e($transaction->customer_name ?? 'Umum'); ?></p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">No Meja / Antrian</p>
            <p style="font-weight:600;"><?php echo e($transaction->customer_phone ?? '-'); ?></p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Metode Pembayaran</p>
            <p style="font-weight:600;text-transform:capitalize;"><?php echo e($transaction->payment_method); ?></p>
          </div>
          <?php if($transaction->notes): ?>
          <div style="grid-column:1/-1;">
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Catatan</p>
            <p style="color:#64748b;"><?php echo e($transaction->notes); ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3>🛒 Item Produk</h3></div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Diskon</th><th>Subtotal</th></tr></thead>
          <tbody>
            <?php $__currentLoopData = $transaction->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <div style="font-weight:600;"><?php echo e($item->product_name); ?></div>
                <div style="font-size:11px;color:#94a3b8;"><?php echo e($item->product->sku ?? ''); ?></div>
              </td>
              <td>Rp <?php echo e(number_format($item->price,0,',','.')); ?></td>
              <td><strong><?php echo e($item->quantity); ?></strong></td>
              <td><?php echo e($item->discount > 0 ? 'Rp '.number_format($item->discount,0,',','.') : '-'); ?></td>
              <td><strong>Rp <?php echo e(number_format($item->subtotal,0,',','.')); ?></strong></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right: Struk -->
  <div>
    <div class="card">
      <div class="card-header"><h3>🧾 Ringkasan Pembayaran</h3></div>
      <div class="card-body">
        <table style="width:100%;font-size:14px;">
          <tr><td style="color:#64748b;padding:6px 0;">Subtotal</td><td style="text-align:right;font-weight:600;">Rp <?php echo e(number_format($transaction->subtotal,0,',','.')); ?></td></tr>
          <?php if($transaction->discount_amount > 0): ?>
          <tr><td style="color:#10b981;padding:6px 0;">Diskon <?php echo e($transaction->discount_percent > 0 ? '('.$transaction->discount_percent.'%)' : ''); ?></td><td style="text-align:right;color:#10b981;font-weight:600;">- Rp <?php echo e(number_format($transaction->discount_amount,0,',','.')); ?></td></tr>
          <?php endif; ?>
          <?php if($transaction->tax_amount > 0): ?>
          <tr><td style="color:#64748b;padding:6px 0;">Pajak (<?php echo e($transaction->tax_percent); ?>%)</td><td style="text-align:right;font-weight:600;">Rp <?php echo e(number_format($transaction->tax_amount,0,',','.')); ?></td></tr>
          <?php endif; ?>
          <tr><td colspan="2"><hr style="border:none;border-top:2px solid #f1f5f9;margin:8px 0;"></td></tr>
          <tr>
            <td style="font-size:16px;font-weight:700;padding:6px 0;">TOTAL</td>
            <td style="text-align:right;font-size:18px;font-weight:700;color:#6366f1;">Rp <?php echo e(number_format($transaction->total,0,',','.')); ?></td>
          </tr>
          <tr><td colspan="2"><hr style="border:none;border-top:1px dashed #e2e8f0;margin:8px 0;"></td></tr>
          <tr><td style="color:#64748b;padding:4px 0;">Dibayar</td><td style="text-align:right;font-weight:600;">Rp <?php echo e(number_format($transaction->amount_paid,0,',','.')); ?></td></tr>
          <tr>
            <td style="padding:4px 0;">Kembalian</td>
            <td style="text-align:right;font-weight:700;color:#10b981;font-size:15px;">Rp <?php echo e(number_format($transaction->change_amount,0,',','.')); ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/transactions/show.blade.php ENDPATH**/ ?>