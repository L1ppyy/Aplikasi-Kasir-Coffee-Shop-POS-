<?php $__env->startSection('title','Laporan'); ?>
<?php $__env->startSection('page-title','Laporan Penjualan'); ?>
<?php $__env->startSection('page-subtitle','Analisis omzet dan performa bisnis'); ?>

<?php $__env->startSection('styles'); ?>
<style>
.report-stat { background:white;border-radius:16px;border:1px solid #e2e8f0;padding:20px 24px; }
.report-stat h2 { font-size:26px;font-weight:700;margin-bottom:4px; }
.report-stat p { font-size:12px;color:#64748b; }
.chart-wrap { display:flex;align-items:flex-end;gap:6px;height:180px;padding:0 4px; }
.bar-col { flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;justify-content:flex-end; }
.bar { width:100%;background:linear-gradient(to top,#6366f1,#a5b4fc);border-radius:6px 6px 0 0;min-height:4px;transition:height 0.5s ease;cursor:pointer; }
.bar:hover { opacity:.8; }
.bar-lbl { font-size:10px;color:#94a3b8;white-space:nowrap; }
.bar-val { font-size:9px;color:#6366f1;font-weight:700; }
.section-title { font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94a3b8;margin-bottom:14px; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Date Filter -->
<div class="card" style="margin-bottom:20px;">
  <div class="card-body" style="padding:16px 20px;">
    <form method="GET" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
      <label style="font-size:13px;font-weight:600;color:#374151;">Periode:</label>
      <input type="date" class="form-control" name="date_from" value="<?php echo e($dateFrom); ?>" style="max-width:160px;">
      <span style="color:#94a3b8;">—</span>
      <input type="date" class="form-control" name="date_to" value="<?php echo e($dateTo); ?>" style="max-width:160px;">
      <button type="submit" class="btn btn-primary">Tampilkan</button>
      <a href="?date_from=<?php echo e(now()->startOfMonth()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>" class="btn btn-secondary">Bulan Ini</a>
      <a href="?date_from=<?php echo e(now()->startOfWeek()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>" class="btn btn-secondary">Minggu Ini</a>
      <a href="?date_from=<?php echo e(now()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>" class="btn btn-secondary">Hari Ini</a>
      <a href="<?php echo e(route('admin.reports.export.excel', ['date_from' => request('date_from'),'date_to' => request('date_to')])); ?>"class="btn btn-success">📊 Export Excel</a>
    </form>
  </div>
</div>



<!-- Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
  <div class="report-stat" style="border-left:4px solid #6366f1;">
    <h2 style="color:#6366f1;">Rp <?php echo e(number_format($summary['total_sales'],0,',','.')); ?></h2>
    <p>💰 Total Penjualan</p>
  </div>
  <div class="report-stat" style="border-left:4px solid #10b981;">
    <h2 style="color:#10b981;"><?php echo e($summary['total_transactions']); ?></h2>
    <p>🧾 Total Transaksi</p>
  </div>
  <div class="report-stat" style="border-left:4px solid #f59e0b;">
    <h2 style="color:#f59e0b;">Rp <?php echo e(number_format($summary['avg_transaction'],0,',','.')); ?></h2>
    <p>📊 Rata-rata Transaksi</p>
  </div>
  <div class="report-stat" style="border-left:4px solid #3b82f6;">
    <h2 style="color:#3b82f6;">Rp <?php echo e(number_format($totalExpenses,0,',','.')); ?></h2>
    <p>💸 Total Pengeluaran</p>
  </div>
  <div class="report-stat" style="border-left:4px solid #ef4444;">
    <h2 style="color:#ef4444;">Rp <?php echo e(number_format($summary['total_discount'],0,',','.')); ?></h2>
    <p>🏷️ Total Diskon</p>
  </div>
  <div class="report-stat" style="border-left:4px solid <?php echo e($summary['net_profit']>=0?'#10b981':'#ef4444'); ?>;">
    <h2 style="color:<?php echo e($summary['net_profit']>=0?'#10b981':'#ef4444'); ?>;">Rp <?php echo e(number_format($summary['net_profit'],0,',','.')); ?></h2>
    <p>📈 Laba Bersih (Estimasi)</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
  <!-- Daily Sales Chart -->
  <div class="card">
    <div class="card-header"><h3>📈 Grafik Penjualan Harian</h3></div>
    <div class="card-body">
      <?php $maxD = collect($dailySales)->max('total') ?: 1; ?>
      <?php if(count($dailySales)>0): ?>
      <div class="chart-wrap">
        <?php $__currentLoopData = $dailySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bar-col">
          <div class="bar-val"><?php echo e(number_format($d->total/1000,0)); ?>K</div>
          <div class="bar" style="height:<?php echo e(max(4,($d->total/$maxD)*160)); ?>px" title="<?php echo e($d->date); ?>: Rp <?php echo e(number_format($d->total,0,',','.')); ?>"></div>
          <div class="bar-lbl"><?php echo e(\Carbon\Carbon::parse($d->date)->format('d/m')); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php else: ?>
      <p style="text-align:center;color:#94a3b8;padding:40px 0;">Tidak ada data untuk periode ini</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Payment Methods -->
  <div class="card">
    <div class="card-header"><h3>💳 Metode Pembayaran</h3></div>
    <div class="card-body">
      <?php
      $pmColors=['cash'=>'#10b981','debit'=>'#3b82f6','credit'=>'#8b5cf6','qris'=>'#f59e0b','transfer'=>'#06b6d4'];
      $pmLabels=['cash'=>'Tunai','debit'=>'Debit','credit'=>'Kredit','qris'=>'QRIS','transfer'=>'Transfer'];
      $pmTotal=$paymentMethods->sum('total')?:1;
      ?>
      <?php $__empty_1 = true; $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
          <span style="font-weight:600;"><?php echo e($pmLabels[$pm->payment_method]??$pm->payment_method); ?></span>
          <span style="color:#64748b;"><?php echo e($pm->count); ?>x · Rp <?php echo e(number_format($pm->total/1000,0)); ?>K</span>
        </div>
        <div style="height:7px;background:#f1f5f9;border-radius:4px;overflow:hidden;">
          <div style="height:100%;width:<?php echo e(($pm->total/$pmTotal)*100); ?>%;background:<?php echo e($pmColors[$pm->payment_method]??'#94a3b8'); ?>;border-radius:4px;"></div>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <p style="text-align:center;color:#94a3b8;padding:20px 0;">Tidak ada data</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <!-- Top Products -->
  <div class="card">
    <div class="card-header"><h3>🏆 Top 10 Produk</h3></div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>#</th><th>Produk</th><th>Qty Terjual</th><th>Revenue</th></tr></thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td>
              <?php if($i==0): ?> <span style="font-size:18px;">🥇</span>
              <?php elseif($i==1): ?> <span style="font-size:18px;">🥈</span>
              <?php elseif($i==2): ?> <span style="font-size:18px;">🥉</span>
              <?php else: ?> <span style="color:#94a3b8;font-weight:600;"><?php echo e($i+1); ?></span>
              <?php endif; ?>
            </td>
            <td>
              <div style="font-weight:600;font-size:13px;"><?php echo e($p->name); ?></div>
              <div style="font-size:11px;color:#94a3b8;"><?php echo e($p->sku); ?></div>
            </td>
            <td><span class="badge badge-purple"><?php echo e($p->qty); ?></span></td>
            <td style="font-weight:700;color:#6366f1;font-size:13px;">Rp <?php echo e(number_format($p->revenue/1000,0)); ?>K</td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:24px;">Tidak ada data</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Expense Breakdown -->
  <div class="card">
    <div class="card-header"><h3>💸 Rincian Pengeluaran</h3></div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Kategori</th><th>Jml</th><th>Total</th></tr></thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $expenseBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td style="font-weight:600;text-transform:capitalize;"><?php echo e($ex->category); ?></td>
            <td><span class="badge badge-gray"><?php echo e($ex->count); ?>x</span></td>
            <td style="font-weight:700;color:#ef4444;">Rp <?php echo e(number_format($ex->total,0,',','.')); ?></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:24px;">Tidak ada pengeluaran</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>