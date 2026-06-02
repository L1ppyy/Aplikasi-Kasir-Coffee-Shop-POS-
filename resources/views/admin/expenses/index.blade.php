@extends('layouts.admin')
@section('title','Pengeluaran')
@section('page-title','Pengeluaran')
@section('page-subtitle','Catat dan kelola biaya operasional')

@section('topbar-actions')
<button onclick="document.getElementById('addModal').classList.add('open')" class="btn btn-primary">➕ Catat Pengeluaran</button>
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
  <div class="card-body" style="padding:16px 20px;">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
      <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" style="max-width:160px;">
      <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" style="max-width:160px;">
      <select class="form-control" name="category" style="max-width:180px;">
        <option value="">Semua Kategori</option>
        @foreach(['operasional','gaji','sewa','utilitas','bahan','lainnya'] as $c)
        <option value="{{ $c }}" {{ request('category')==$c?'selected':'' }}>{{ ucfirst($c) }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Reset</a>
    </form>
  </div>
</div>

<div style="display:flex;gap:16px;margin-bottom:20px;">
  <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;padding:18px 24px;flex:1;border-left:4px solid #ef4444;">
    <h2 style="font-size:22px;font-weight:700;color:#ef4444;">Rp {{ number_format($total,0,',','.') }}</h2>
    <p style="font-size:12px;color:#64748b;">Total Pengeluaran Terfilter</p>
  </div>
</div>

<div class="card">
  <div class="card-header"><h3>💸 Daftar Pengeluaran</h3></div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th>Dicatat oleh</th><th>Jumlah</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($expenses as $exp)
        <tr>
          <td style="font-size:13px;">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</td>
          <td>
            <div style="font-weight:600;">{{ $exp->title }}</div>
            @if($exp->description)<div style="font-size:11px;color:#94a3b8;">{{ $exp->description }}</div>@endif
          </td>
          <td>
            @php $catColors=['operasional'=>'#6366f1','gaji'=>'#10b981','sewa'=>'#f59e0b','utilitas'=>'#3b82f6','bahan'=>'#8b5cf6','lainnya'=>'#64748b'] @endphp
            <span style="background:{{ ($catColors[$exp->category]??'#64748b') }}22;color:{{ $catColors[$exp->category]??'#64748b' }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;text-transform:capitalize;">{{ $exp->category }}</span>
          </td>
          <td style="font-size:13px;color:#64748b;">{{ $exp->user->name ?? '-' }}</td>
          <td><strong style="color:#ef4444;">Rp {{ number_format($exp->amount,0,',','.') }}</strong></td>
          <td>
            <form action="{{ route('admin.expenses.destroy',$exp) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">Belum ada pengeluaran tercatat</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">{{ $expenses->links() }}</div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
  <div class="modal">
    <div class="modal-header">
      <h3>➕ Catat Pengeluaran</h3>
      <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('open')">✕</button>
    </div>
    <form action="{{ route('admin.expenses.store') }}" method="POST">
      @csrf
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
            <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Kategori *</label>
            <select name="category" class="form-control" required>
              @foreach(['operasional'=>'Operasional','gaji'=>'Gaji Karyawan','sewa'=>'Sewa Tempat','utilitas'=>'Utilitas (Listrik/Air)','bahan'=>'Pembelian Bahan','lainnya'=>'Lainnya'] as $v=>$l)
              <option value="{{ $v }}">{{ $l }}</option>
              @endforeach
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
@endsection

@section('scripts')
<script>
document.getElementById('addModal').addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});
</script>
@endsection
