@extends('layouts.admin')
@section('title','Detail Transaksi')
@section('page-title','Detail Transaksi')
@section('page-subtitle', $transaction->invoice_number)

@section('topbar-actions')
<a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">← Kembali</a>
<button onclick="window.print()" class="btn btn-primary">🖨️ Cetak</button>
@endsection

@section('styles')
<style>
@media print {
  .sidebar,.topbar,.topbar-actions,.btn,.alert { display:none!important; }
  .main-content { margin-left:0!important; }
  .page-content { padding:0!important; }
}
.receipt-box { max-width:380px; margin:0 auto; font-family:'Courier New',monospace; border:2px dashed #e2e8f0; border-radius:16px; padding:24px; }
.receipt-divider { border:none; border-top:1px dashed #e2e8f0; margin:12px 0; }
</style>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;">

  <!-- Left: Detail -->
  <div>
    <div class="card" style="margin-bottom:20px;">
      <div class="card-header" style="justify-content:space-between;">
        <h3>📋 Informasi Transaksi</h3>
        <div style="display:flex;gap:8px;align-items:center;">
          @if($transaction->status=='completed')    <span class="badge badge-success" style="font-size:13px;padding:5px 14px;">✅ Selesai</span>
          @elseif($transaction->status=='cancelled') <span class="badge badge-danger" style="font-size:13px;padding:5px 14px;">❌ Dibatalkan</span>
          @elseif($transaction->status=='refunded')  <span class="badge badge-warning" style="font-size:13px;padding:5px 14px;">↩️ Refund</span>
          @else                                      <span class="badge badge-gray" style="font-size:13px;padding:5px 14px;">⏳ Pending</span>
          @endif

          <form action="{{ route('admin.transactions.status',$transaction) }}" method="POST" style="display:flex;gap:6px;">
            @csrf @method('PATCH')
            <select name="status" class="form-control" style="max-width:150px;">
              <option value="completed"  {{ $transaction->status=='completed'?'selected':'' }}>Selesai</option>
              <option value="pending"    {{ $transaction->status=='pending'?'selected':'' }}>Pending</option>
              <option value="cancelled"  {{ $transaction->status=='cancelled'?'selected':'' }}>Dibatalkan</option>
              <option value="refunded"   {{ $transaction->status=='refunded'?'selected':'' }}>Refund</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">No. Invoice</p>
            <p style="font-weight:700;color:#6366f1;font-size:16px;">{{ $transaction->invoice_number }}</p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal & Waktu</p>
            <p style="font-weight:600;">{{ $transaction->created_at->format('d F Y, H:i') }}</p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kasir</p>
            <p style="font-weight:600;">{{ $transaction->user->name ?? '-' }}</p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Pelanggan</p>
            <p style="font-weight:600;">{{ $transaction->customer_name ?? 'Umum' }}</p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">No Meja / Antrian</p>
            <p style="font-weight:600;">{{ $transaction->customer_phone ?? '-' }}</p>
          </div>
          <div>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Metode Pembayaran</p>
            <p style="font-weight:600;text-transform:capitalize;">{{ $transaction->payment_method }}</p>
          </div>
          @if($transaction->notes)
          <div style="grid-column:1/-1;">
            <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Catatan</p>
            <p style="color:#64748b;">{{ $transaction->notes }}</p>
          </div>
          @endif
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3>🛒 Item Produk</h3></div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Diskon</th><th>Subtotal</th></tr></thead>
          <tbody>
            @foreach($transaction->items as $item)
            <tr>
              <td>
                <div style="font-weight:600;">{{ $item->product_name }}</div>
                <div style="font-size:11px;color:#94a3b8;">{{ $item->product->sku ?? '' }}</div>
              </td>
              <td>Rp {{ number_format($item->price,0,',','.') }}</td>
              <td><strong>{{ $item->quantity }}</strong></td>
              <td>{{ $item->discount > 0 ? 'Rp '.number_format($item->discount,0,',','.') : '-' }}</td>
              <td><strong>Rp {{ number_format($item->subtotal,0,',','.') }}</strong></td>
            </tr>
            @endforeach
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
          <tr><td style="color:#64748b;padding:6px 0;">Subtotal</td><td style="text-align:right;font-weight:600;">Rp {{ number_format($transaction->subtotal,0,',','.') }}</td></tr>
          @if($transaction->discount_amount > 0)
          <tr><td style="color:#10b981;padding:6px 0;">Diskon {{ $transaction->discount_percent > 0 ? '('.$transaction->discount_percent.'%)' : '' }}</td><td style="text-align:right;color:#10b981;font-weight:600;">- Rp {{ number_format($transaction->discount_amount,0,',','.') }}</td></tr>
          @endif
          @if($transaction->tax_amount > 0)
          <tr><td style="color:#64748b;padding:6px 0;">Pajak ({{ $transaction->tax_percent }}%)</td><td style="text-align:right;font-weight:600;">Rp {{ number_format($transaction->tax_amount,0,',','.') }}</td></tr>
          @endif
          <tr><td colspan="2"><hr style="border:none;border-top:2px solid #f1f5f9;margin:8px 0;"></td></tr>
          <tr>
            <td style="font-size:16px;font-weight:700;padding:6px 0;">TOTAL</td>
            <td style="text-align:right;font-size:18px;font-weight:700;color:#6366f1;">Rp {{ number_format($transaction->total,0,',','.') }}</td>
          </tr>
          <tr><td colspan="2"><hr style="border:none;border-top:1px dashed #e2e8f0;margin:8px 0;"></td></tr>
          <tr><td style="color:#64748b;padding:4px 0;">Dibayar</td><td style="text-align:right;font-weight:600;">Rp {{ number_format($transaction->amount_paid,0,',','.') }}</td></tr>
          <tr>
            <td style="padding:4px 0;">Kembalian</td>
            <td style="text-align:right;font-weight:700;color:#10b981;font-size:15px;">Rp {{ number_format($transaction->change_amount,0,',','.') }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
