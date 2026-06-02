<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi — Kasir</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Space Grotesk',sans-serif;background:#f1f5f9;color:#0f172a;min-height:100vh;}
.topbar{background:#0f172a;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;}
.topbar-logo{color:#f8fafc;font-weight:700;font-size:16px;}
.topbar-btn{padding:7px 16px;border-radius:8px;font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;cursor:pointer;border:none;text-decoration:none;background:rgba(255,255,255,.1);color:#f1f5f9;transition:background .2s;}
.topbar-btn:hover{background:rgba(255,255,255,.2);}
.container{max-width:1100px;margin:0 auto;padding:30px 20px;}
.card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;}
.card-header{padding:18px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;}
.card-header h2{font-size:17px;font-weight:700;}
table{width:100%;border-collapse:collapse;}
th{padding:11px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;background:#f8fafc;border-bottom:1px solid #e2e8f0;}
td{padding:13px 16px;font-size:14px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:#f8fafc;}
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-success{background:#d1fae5;color:#065f46;}
.badge-danger{background:#fee2e2;color:#991b1b;}
.badge-warning{background:#fef3c7;color:#92400e;}
.badge-info{background:#dbeafe;color:#1e40af;}
.page-title{margin-bottom:20px;}
.page-title h1{font-size:24px;font-weight:700;}
.page-title p{color:#64748b;margin-top:4px;}
.pagination{display:flex;gap:4px;margin-top:20px;}
.pagination a,.pagination span{padding:7px 13px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;}
.pagination a{background:#fff;border:1px solid #e2e8f0;color:#0f172a;}
.pagination a:hover{background:#f1f5f9;}
.pagination .active{background:#6366f1;color:#fff;}
</style>
</head>
<body>
<div class="topbar">
  <span class="topbar-logo">🏪 Riwayat Transaksi</span>
  <div style="display:flex;gap:8px;">
    <a href="<?php echo e(route('cashier.index')); ?>" class="topbar-btn">← Kembali ke Kasir</a>
  </div>
</div>

<div class="container">
  <div class="page-title">
    <h1>📋 Riwayat Transaksi Saya</h1>
    <p>Daftar semua transaksi yang Anda proses</p>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>Transaksi</h2>
      <span style="background:#ede9fe;color:#6366f1;padding:2px 10px;border-radius:20px;font-size:12px;font-weight:700;"><?php echo e($transactions->total()); ?> total</span>
    </div>
    <table>
      <thead>
        <tr>
          <th>Invoice</th><th>Pelanggan</th><th>Items</th>
          <th>Total</th><th>Pembayaran</th><th>Status</th><th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td><strong style="color:#6366f1;font-size:13px;"><?php echo e($trx->invoice_number); ?></strong></td>
          <td><?php echo e($trx->customer_name ?? 'Umum'); ?></td>
          <td><span class="badge" style="background:#ede9fe;color:#6366f1;"><?php echo e($trx->items->count()); ?> item</span></td>
          <td><strong>Rp <?php echo e(number_format($trx->total,0,',','.')); ?></strong></td>
          <td style="text-transform:capitalize;font-size:13px;"><?php echo e($trx->payment_method); ?></td>
          <td>
            <?php if($trx->status=='completed'): ?> <span class="badge badge-success">Selesai</span>
            <?php elseif($trx->status=='cancelled'): ?> <span class="badge badge-danger">Dibatalkan</span>
            <?php else: ?> <span class="badge badge-warning"><?php echo e($trx->status); ?></span>
            <?php endif; ?>
          </td>
          <td style="font-size:12px;color:#94a3b8;"><?php echo e($trx->created_at->format('d/m/Y H:i')); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;">Belum ada transaksi</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;"><?php echo e($transactions->links()); ?></div>
  </div>
</div>
</body>
</html>
<?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/cashier/history.blade.php ENDPATH**/ ?>