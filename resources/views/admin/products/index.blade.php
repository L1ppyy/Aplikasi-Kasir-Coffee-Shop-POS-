@extends('layouts.admin')
@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'CRUD produk, stok, dan harga')

@section('topbar-actions')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary">➕ Tambah Produk</a>
@endsection

@section('content')
<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <input class="form-control" name="search" value="{{ request('search') }}" placeholder="🔍 Cari nama, SKU, barcode..." style="max-width:280px;">
            <select class="form-control" name="category_id" style="max-width:180px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select class="form-control" name="status" style="max-width:180px;">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Nonaktif</option>
                <option value="low_stock" {{ request('status')=='low_stock'?'selected':'' }}>Stok Menipis</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📦 Daftar Produk <span style="background:#ede9fe;color:#6366f1;padding:2px 10px;border-radius:20px;font-size:12px;margin-left:8px;">{{ $products->total() }}</span></h3>
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
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:42px;height:42px;border-radius:10px;background:{{ $product->category->color ?? '#e2e8f0' }}22;display:flex;align-items:center;justify-content:center;font-size:20px;border:1px solid {{ $product->category->color ?? '#e2e8f0' }}44;">
                                @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                                @else
                                📦
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $product->name }}</div>
                                <div style="font-size:11px;color:#94a3b8;">{{ $product->barcode ?? 'Tanpa barcode' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="background:{{ $product->category->color ?? '#94a3b8' }}22;color:{{ $product->category->color ?? '#94a3b8' }};padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                            {{ $product->category->name ?? '-' }}
                        </span>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:#64748b;">{{ $product->sku }}</td>
                    <td><strong style="color:#0f172a;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</strong></td>
                    <td style="color:#64748b;font-size:13px;">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-weight:700;color:{{ $product->stock == 0 ? '#ef4444' : ($product->isLowStock() ? '#f59e0b' : '#10b981') }}">
                                {{ $product->stock }}
                            </span>
                            <span style="font-size:11px;color:#94a3b8;">{{ $product->unit }}</span>
                            @if($product->isLowStock())
                            <span class="badge badge-warning" style="font-size:9px;">Menipis</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($product->is_active)
                        <span class="badge badge-success">Aktif</span>
                        @else
                        <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                            <button onclick="openStockModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})" class="btn btn-warning btn-sm">📦 Stok</button>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;">
                    <div style="font-size:40px;margin-bottom:12px;">📦</div>
                    <div>Tidak ada produk ditemukan</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
        {{ $products->links() }}
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
            @csrf
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
@endsection

@section('scripts')
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
@endsection
