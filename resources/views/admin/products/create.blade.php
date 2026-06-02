@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')
@section('page-subtitle', 'Isi data produk dengan lengkap')

@section('content')
<div style="max-width: 800px;">
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header"><h3>📋 Informasi Dasar</h3></div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Nasi Goreng Spesial" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SKU (Kode Produk) *</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="Contoh: MKN001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}" placeholder="Scan atau isi manual">
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan *</label>
                    <select name="unit" class="form-control" required>
                        <option value="pcs">pcs</option>
                        <option value="porsi">porsi</option>
                        <option value="gelas">gelas</option>
                        <option value="botol">botol</option>
                        <option value="kg">kg</option>
                        <option value="gram">gram</option>
                        <option value="liter">liter</option>
                        <option value="pack">pack</option>
                        <option value="box">box</option>
                        <option value="strip">strip</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
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
                    <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', 0) }}" min="0" step="100" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Jual *</label>
                    <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price') }}" min="0" step="100" required placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Awal *</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Minimum (Alert)</label>
                    <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 5) }}" min="0">
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header"><h3>🖼️ Gambar Produk</h3></div>
        <div class="card-body">
            <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
            <img id="preview" style="margin-top:12px;max-width:200px;border-radius:12px;display:none;">
        </div>
    </div>

    <div style="display:flex;gap:12px;">
        <button type="submit" class="btn btn-primary">✅ Simpan Produk</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
</div>
@endsection

@section('scripts')
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
@endsection
