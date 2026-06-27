<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kasir — {{ $settings['store_name'] }}</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --brand:#0f172a;--accent:#6366f1;--accent2:#f59e0b;
  --success:#10b981;--danger:#ef4444;--border:#e2e8f0;
  --bg:#f1f5f9;--surface:#fff;--muted:#64748b;--text:#0f172a;
}
body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);height:100vh;display:flex;flex-direction:column;overflow:hidden;}

/* TOPBAR */
.pos-topbar{background:var(--brand);height:52px;display:flex;align-items:center;justify-content:space-between;padding:0 20px;flex-shrink:0;}
.pos-topbar-left{display:flex;align-items:center;gap:12px;}
.pos-logo{color:#f8fafc;font-weight:700;font-size:16px;}
.pos-time{color:#94a3b8;font-size:13px;}
.pos-topbar-right{display:flex;align-items:center;gap:8px;}
.topbar-btn{display:flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .2s;text-decoration:none;}
.topbar-btn-ghost{background:rgba(255,255,255,.08);color:#f1f5f9;}
.topbar-btn-ghost:hover{background:rgba(255,255,255,.14);}
.topbar-btn-danger{background:rgba(239,68,68,.15);color:#f87171;}
.topbar-btn-danger:hover{background:rgba(239,68,68,.25);}
.cashier-name{color:#94a3b8;font-size:12px;}

/* LAYOUT */
.pos-body{flex:1;display:flex;overflow:hidden;}

/* LEFT PANEL - Products */
.products-panel{flex:1;display:flex;flex-direction:column;overflow:hidden;border-right:1px solid var(--border);}

/* Search & Filter */
.search-bar{padding:12px 16px;background:var(--surface);border-bottom:1px solid var(--border);display:flex;gap:10px;align-items:center;}
.search-input{flex:1;padding:9px 14px 9px 38px;border:1.5px solid var(--border);border-radius:10px;font-family:'Space Grotesk',sans-serif;font-size:14px;outline:none;transition:all .2s;background:#f8fafc;}
.search-input:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
.search-wrap{position:relative;flex:1;}
.search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:16px;}

/* Categories */
.cat-bar{padding:10px 16px;background:var(--surface);border-bottom:1px solid var(--border);display:flex;gap:8px;overflow-x:auto;}
.cat-bar::-webkit-scrollbar{height:3px;}
.cat-bar::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:3px;}
.cat-btn{flex-shrink:0;padding:6px 16px;border-radius:20px;border:1.5px solid var(--border);background:var(--surface);font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap;}
.cat-btn:hover{border-color:var(--accent);color:var(--accent);}
.cat-btn.active{background:var(--accent);border-color:var(--accent);color:#fff;}

/* Product Grid */
.product-grid-wrap{flex:1;overflow-y:auto;padding:14px;}
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;}
.product-card{background:var(--surface);border-radius:12px;border:1.5px solid var(--border);padding:12px;cursor:pointer;transition:all .2s;position:relative;user-select:none;}
.product-card:hover{border-color:var(--accent);box-shadow:0 4px 16px rgba(99,102,241,.12);transform:translateY(-2px);}
.product-card.out-of-stock{opacity:.5;cursor:not-allowed;pointer-events:none;}
.product-card.out-of-stock::after{content:'Habis';position:absolute;top:8px;right:8px;background:var(--danger);color:#fff;font-size:9px;font-weight:700;padding:2px 7px;border-radius:10px;}
.product-img{width:100%;height:80px;border-radius:8px;object-fit:cover;background:#f8fafc;display:flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:8px;}
.product-img-placeholder{width:100%;height:80px;border-radius:8px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);display:flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:8px;}
.product-name{font-size:13px;font-weight:600;line-height:1.3;margin-bottom:4px;color:var(--text);}
.product-price{font-size:14px;font-weight:700;color:var(--accent);}
.product-stock{font-size:11px;color:var(--muted);margin-top:2px;}
.product-stock.low{color:var(--accent2);}

/* RIGHT PANEL - Cart */
.cart-panel{width:360px;
    background:var(--surface);
    display:flex;
    flex-direction:column;
    flex-shrink:0;
    min-height:0;}

/* Cart Header */
.cart-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.cart-header h3{font-size:15px;font-weight:700;}
.cart-count{background:var(--accent);color:#fff;border-radius:20px;font-size:11px;font-weight:700;padding:1px 8px;}
.btn-clear{background:none;border:none;color:var(--muted);font-size:12px;cursor:pointer;font-family:'Space Grotesk',sans-serif;padding:4px 8px;border-radius:6px;transition:all .2s;}
.btn-clear:hover{background:#fee2e2;color:var(--danger);}

/* Customer Info */
.customer-bar{padding:10px 16px;border-bottom:1px solid var(--border);display:flex;gap:8px;}
.customer-bar input{flex:1;padding:7px 10px;border:1.5px solid var(--border);border-radius:8px;font-family:'Space Grotesk',sans-serif;font-size:12px;outline:none;}
.customer-bar input:focus{border-color:var(--accent);}

/* Cart Items */
.cart-items{flex:1;
    min-height:0;
    overflow-y:auto;
    padding:10px;}
.cart-items::-webkit-scrollbar{width:3px;}
.cart-items::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:3px;}
.empty-cart{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--muted);gap:10px;padding:30px;}
.empty-cart .icon{font-size:48px;opacity:.3;}
.cart-item{display:flex;gap:10px;align-items:center;padding:8px 6px;border-radius:10px;margin-bottom:6px;transition:background .15s;}
.cart-item:hover{background:#f8fafc;}
.cart-item-info{flex:1;min-width:0;}
.cart-item-name{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.cart-item-price{font-size:12px;color:var(--muted);}
.cart-item-subtotal{font-size:13px;font-weight:700;color:var(--accent);white-space:nowrap;}
.qty-ctrl{display:flex;align-items:center;gap:4px;}
.qty-btn{width:26px;height:26px;border-radius:7px;border:1.5px solid var(--border);background:var(--surface);font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;line-height:1;}
.qty-btn:hover{background:var(--accent);border-color:var(--accent);color:#fff;}
.qty-btn.minus:hover{background:var(--danger);border-color:var(--danger);}
.qty-val{min-width:28px;text-align:center;font-weight:700;font-size:14px;}
.item-del{background:none;border:none;color:#cbd5e1;cursor:pointer;font-size:16px;padding:2px;transition:color .15s;}
.item-del:hover{color:var(--danger);}

/* Cart Footer */
.cart-footer{border-top:1px solid var(--border);
    padding:10px 12px;
    flex-shrink:0;}
.summary-row{display:flex;justify-content:space-between;font-size:13px;margin-bottom:7px;}
.summary-row .label{color:var(--muted);}
.summary-row .val{font-weight:600;}
.discount-row{display:flex;gap:6px;margin-bottom:10px;}
.discount-row input{flex:1;padding:7px 10px;border:1.5px solid var(--border);border-radius:8px;font-family:'Space Grotesk',sans-serif;font-size:12px;outline:none;}
.discount-row input:focus{border-color:var(--accent);}
.discount-row select{padding:7px 8px;border:1.5px solid var(--border);border-radius:8px;font-size:12px;font-family:'Space Grotesk',sans-serif;outline:none;cursor:pointer;}
.total-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-top:2px solid var(--border);margin-top:4px;}
.total-label{font-size:14px;font-weight:700;}
.total-val{font-size:22px;font-weight:700;color:var(--accent);}

/* Payment Section */
.payment-section{padding:10px 12px;
    border-top:1px solid var(--border);
    flex-shrink:0;}
.payment-methods{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:12px;}
.pm-btn{padding:8px 4px;border-radius:9px;border:1.5px solid var(--border);background:var(--surface);font-family:'Space Grotesk',sans-serif;font-size:11px;font-weight:600;cursor:pointer;transition:all .2s;text-align:center;}
.pm-btn:hover{border-color:var(--accent);color:var(--accent);}
.pm-btn.active{background:var(--accent);border-color:var(--accent);color:#fff;}
.pm-btn .pm-icon{font-size:16px;display:block;margin-bottom:2px;}
.cash-input-row{display:flex;gap:8px;margin-bottom:10px;}
.cash-input{flex:1;padding:8px 10px;border:1.5px solid var(--border);border-radius:10px;font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:600;outline:none;transition:all .2s;}
.cash-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1);}
.change-display{padding:8px 12px;background:#f0fdf4;border:1.5px solid #a7f3d0;border-radius:10px;display:flex;justify-content:space-between;align-items:center;margin-bottom:9px;}
.change-label{font-size:12px;color:var(--success);font-weight:600;}
.change-val{font-size:16px;font-weight:700;color:var(--success);}
.quick-cash{display:flex;gap:6px;margin-bottom:10px;flex-wrap:wrap;}
.quick-cash-btn{flex:1;min-width:fit-content;padding:5px 6px;border:1.5px solid var(--border);border-radius:8px;background:var(--surface);font-size:11px;font-weight:600;cursor:pointer;transition:all .2s;font-family:'Space Grotesk',sans-serif;}
.quick-cash-btn:hover{background:var(--bg);border-color:var(--accent);color:var(--accent);}
.btn-checkout{width:100%;padding:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:12px;font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;letter-spacing:.3px;}
.btn-checkout:hover{box-shadow:0 6px 20px rgba(99,102,241,.4);transform:translateY(-1px);}
.btn-checkout:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none;}

/* Receipt Modal */
.receipt-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
.receipt-overlay.open{display:flex;}
.receipt-box{background:#fff;border-radius:20px;padding:0;max-width:360px;width:90%;max-height:90vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 25px 60px rgba(0,0,0,.3);}
.receipt-header{background:var(--brand);color:#fff;padding:20px 24px;text-align:center;}
.receipt-header h3{font-size:18px;font-weight:700;}
.receipt-header p{font-size:12px;opacity:.6;margin-top:2px;}
.receipt-body{padding:20px 24px;overflow-y:auto;font-family:'Courier New',monospace;font-size:13px;}
.receipt-row{display:flex;justify-content:space-between;margin-bottom:5px;}
.receipt-div{border:none;border-top:1px dashed #e2e8f0;margin:10px 0;}
.receipt-total{font-size:16px;font-weight:700;}
.receipt-footer-box{padding:16px 24px;border-top:1px solid var(--border);display:flex;gap:10px;}
.btn-print{flex:1;padding:11px;background:var(--brand);color:#fff;border:none;border-radius:10px;font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;cursor:pointer;}
.btn-new{flex:1;padding:11px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;border-radius:10px;font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;cursor:pointer;}

/* Notif toast */
.toast{position:fixed;top:16px;right:16px;z-index:9999;background:var(--brand);color:#fff;padding:12px 20px;border-radius:12px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;transform:translateY(-80px);transition:transform .3s ease;box-shadow:0 8px 24px rgba(0,0,0,.2);}
.toast.show{transform:translateY(0);}
.toast.success{background:var(--success);}
.toast.error{background:var(--danger);}

@media print {
  /* 1. Sembunyikan total seluruh elemen bawaan halaman web aplikasi kasir */
  body * {
    visibility: hidden;
  }
  
  /* 2. Paksa hanya kotak struk (receipt-box) beserta isinya saja yang terlihat */
  .receipt-overlay, .receipt-box, .receipt-box * {
    visibility: visible !important;
  }
  
  /* 3. Atur layout penempatan kotak struk presisi ke pojok kiri atas kertas printer kasir */
  .receipt-overlay {
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    width: 100% !important;
    height: auto !important;
    background: none !important;
    backdrop-filter: none !important;
    display: flex !important;
  }
  
  .receipt-box {
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    width: 80mm !important; /* Standar lebar kertas printer thermal */
    box-shadow: none !important;
    max-height: none !important;
    border-radius: 0 !important;
    background: #fff !important;
  }

  /* 4. Sembunyikan tombol 'Cetak' dan 'Transaksi Baru' agar tidak ikut terprint */
  .receipt-footer-box {
    display: none !important;
  }

  /* 5. Bersihkan teks otomatis bawaan browser (seperti URL 127.0.0.1 dan tanggal) */
  @page {
    size: auto;
    margin: 0mm;
  }
}
</style>
</head>
<body>
<!-- Topbar -->
<div class="pos-topbar">
  <div class="pos-topbar-left">
    <span class="pos-logo">🏪 {{ $settings['store_name'] }}</span>
    <span class="pos-time" id="posTime"></span>
  </div>
  <div class="pos-topbar-right">
    <span class="cashier-name">👤 {{ auth()->user()->name }}</span>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}" class="topbar-btn topbar-btn-ghost">📊 Admin</a>
    @endif
    <a href="{{ route('cashier.history') }}" class="topbar-btn topbar-btn-ghost">📋 Riwayat</a>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
      @csrf
      <button type="submit" class="topbar-btn topbar-btn-danger">🚪 Keluar</button>
    </form>
  </div>
</div>

<div class="pos-body">
  <!-- Products Panel -->
  <div class="products-panel">
    <!-- Search -->
    <div class="search-bar">
      <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari produk, SKU, barcode..." autocomplete="off">
      </div>
    </div>
    <!-- Categories -->
    <div class="cat-bar">
      <button class="cat-btn active" data-cat="all">Semua</button>
      @foreach($categories as $cat)
      @if($cat->products_count > 0)
      <button class="cat-btn" data-cat="{{ $cat->id }}" style="--cat-color:{{ $cat->color }}">
        {{ $cat->icon ?? '' }} {{ $cat->name }}
      </button>
      @endif
      @endforeach
    </div>
    <!-- Product Grid -->
    <div class="product-grid-wrap">
      <div class="product-grid" id="productGrid">
        @foreach($products as $product)
        <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
             data-id="{{ $product->id }}"
             data-name="{{ $product->name }}"
             data-price="{{ $product->selling_price }}"
             data-stock="{{ $product->stock }}"
             data-cat="{{ $product->category_id }}"
             onclick="addToCart(this)">
          <div class="product-img-placeholder">
            @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
            @else
            {{ $product->category->icon ?? '📦' }}
            @endif
          </div>
          <div class="product-name">{{ $product->name }}</div>
          <div class="product-price">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
          <div class="product-stock {{ $product->stock <= $product->min_stock ? 'low' : '' }}">
            Stok: {{ $product->stock }} {{ $product->unit }}
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Cart Panel -->
  <div class="cart-panel">
    <!-- Header -->
    <div class="cart-header">
      <div style="display:flex;align-items:center;gap:8px;">
        <h3>🛒 Keranjang</h3>
        <span class="cart-count" id="cartCount">0</span>
      </div>
      <button class="btn-clear" onclick="clearCart()">🗑️ Kosongkan</button>
    </div>

    <!-- Customer -->
    <div class="customer-bar">
      <input type="text" id="customerName" placeholder="👤 Nama pelanggan (opsional)">
      <input type="text" id="customerTableQueue" placeholder="🔢 No. Meja / Antrean">
    </div>

    <!-- Cart Items -->
    <div class="cart-items" id="cartItems">
      <div class="empty-cart" id="emptyCart">
        <div class="icon">🛒</div>
        <div style="font-weight:600;">Keranjang kosong</div>
        <div style="font-size:13px;">Pilih produk untuk ditambahkan</div>
      </div>
    </div>

    <!-- Summary -->
    <div class="cart-footer">
      <!-- Summary rows -->
      <div class="summary-row"><span class="label">Subtotal</span><span class="val" id="subtotalDisplay">Rp 0</span></div>
      <div class="summary-row" id="taxDisplay" style="{{ $settings['tax_percent']>0?'':'display:none' }}">
        <span class="label">Pajak ({{ $settings['tax_percent'] }}%)</span>
        <span class="val" id="taxAmt">Rp 0</span>
      </div>
      <div class="total-row">
        <span class="total-label">TOTAL</span>
        <span class="total-val" id="totalDisplay">Rp 0</span>
      </div>
    </div>

    <!-- Payment -->
    <div class="payment-section">
      <div style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:8px;">METODE PEMBAYARAN</div>
      <div class="payment-methods">
        <button class="pm-btn active" data-pm="cash" onclick="selectPayment('cash',this)"><span class="pm-icon">💵</span>Tunai</button>
        <button class="pm-btn" data-pm="qris" onclick="selectPayment('qris',this)"><span class="pm-icon">📱</span>QRIS</button>
        <button class="pm-btn" data-pm="debit" onclick="selectPayment('debit',this)"><span class="pm-icon">💳</span>Debit</button>
      </div>

      <div id="cashSection">
  <div style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:6px;">UANG DITERIMA</div>
  <div class="cash-input-row">
    <input type="number" class="cash-input" id="amountPaid" placeholder="0" oninput="calcChange()" min="0">
  </div>
  <div class="change-display">
    <span class="change-label">💰 Kembalian</span>
    <span class="change-val" id="changeDisplay">Rp 0</span>
  </div>
</div>

      <button class="btn-checkout" id="checkoutBtn" onclick="checkout()" disabled>
        ✅ Bayar Sekarang
      </button>
    </div>
  </div>
</div>

<!-- Receipt Modal -->
<div class="receipt-overlay" id="receiptModal">
  <div class="receipt-box">
    <div class="receipt-header">
      <h3 id="rcStoreName"></h3>
      <p id="rcStoreAddress"></p>
    </div>
    <div class="receipt-body" id="receiptBody"></div>
    <div class="receipt-footer-box">
      <button class="btn-print" onclick="printReceipt()">🖨️ Cetak</button>
      <button class="btn-new" onclick="newTransaction()">✅ Transaksi Baru</button>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script>
const TAX_PERCENT = {{ $settings['tax_percent'] ?? 0 }};
let cart = [];
let selectedPayment = 'cash';
let allProducts = Array.from(document.querySelectorAll('.product-card'));
let lastTransaction = null;

// Clock
function updateTime() {
  const now = new Date();
  document.getElementById('posTime').textContent = now.toLocaleDateString('id-ID', {weekday:'long',day:'numeric',month:'long',year:'numeric'}) + ' · ' + now.toLocaleTimeString('id-ID');
}
updateTime(); setInterval(updateTime, 1000);

// Toast
function showToast(msg, type='') {
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = 'toast' + (type?' '+type:'');
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2500);
}

// Search & Filter
document.getElementById('searchInput').addEventListener('input', filterProducts);
document.querySelectorAll('.cat-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    filterProducts();
  });
});

function filterProducts() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const cat = document.querySelector('.cat-btn.active').dataset.cat;
  allProducts.forEach(card => {
    const matchName = card.dataset.name.toLowerCase().includes(q);
    const matchCat = cat === 'all' || card.dataset.cat === cat;
    card.style.display = (matchName && matchCat) ? '' : 'none';
  });
}

// Add to Cart
function addToCart(card) {
  const id = parseInt(card.dataset.id);
  const name = card.dataset.name;
  const price = parseFloat(card.dataset.price);
  const maxStock = parseInt(card.dataset.stock);

  const existing = cart.find(i => i.id === id);
  if (existing) {
    if (existing.qty >= maxStock) { showToast('⚠️ Stok tidak mencukupi!', 'error'); return; }
    existing.qty++;
    existing.subtotal = existing.price * existing.qty;
  } else {
    cart.push({ id, name, price, qty: 1, subtotal: price, maxStock });
  }
  renderCart();
  showToast('✅ ' + name + ' ditambahkan');
}

function changeQty(id, delta) {
  const item = cart.find(i => i.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) { cart = cart.filter(i => i.id !== id); }
  else {
    if (item.qty > item.maxStock) { item.qty = item.maxStock; showToast('⚠️ Stok maksimal!', 'error'); }
    item.subtotal = item.price * item.qty;
  }
  renderCart();
}

function removeItem(id) { cart = cart.filter(i => i.id !== id); renderCart(); }
function clearCart() { cart = []; renderCart(); }

function renderCart() {
  const container = document.getElementById('cartItems');
  const empty = document.getElementById('emptyCart');
  const count = cart.reduce((s, i) => s + i.qty, 0);
  document.getElementById('cartCount').textContent = count;

  if (cart.length === 0) {
    container.innerHTML = ''; container.appendChild(empty); empty.style.display = 'flex';
    document.getElementById('checkoutBtn').disabled = true;
    recalculate(); return;
  }

  empty.style.display = 'none';
  const existing = container.querySelector('#cartItemsList');
  const list = existing || document.createElement('div');
  list.id = 'cartItemsList'; list.style.width = '100%';

  list.innerHTML = cart.map(item => `
    <div class="cart-item">
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">Rp ${fmtNum(item.price)}</div>
      </div>
      <div class="qty-ctrl">
        <button class="qty-btn minus" onclick="changeQty(${item.id},-1)">−</button>
        <span class="qty-val">${item.qty}</span>
        <button class="qty-btn" onclick="changeQty(${item.id},1)">+</button>
      </div>
      <div class="cart-item-subtotal">Rp ${fmtNum(item.subtotal)}</div>
      <button class="item-del" onclick="removeItem(${item.id})">✕</button>
    </div>
  `).join('');

  if (!existing) container.appendChild(list);
  recalculate();
  document.getElementById('checkoutBtn').disabled = false;
}

function fmtNum(n) { return Number(n).toLocaleString('id-ID'); }

function recalculate() {
  const subtotal = cart.reduce((s, i) => s + i.subtotal, 0);

  const taxAmt = subtotal * TAX_PERCENT / 100;
  const total = subtotal + taxAmt;

  document.getElementById('subtotalDisplay').textContent =
    'Rp ' + fmtNum(subtotal);

  document.getElementById('totalDisplay').textContent =
    'Rp ' + fmtNum(total);

  if (TAX_PERCENT > 0) {
    document.getElementById('taxAmt').textContent =
      'Rp ' + fmtNum(taxAmt);
  }

  // const qc = document.getElementById('quickCashButtons');

  // const denominations = [
  //   total,
  //   Math.ceil(total / 10000) * 10000,
  //   Math.ceil(total / 50000) * 50000,
  //   Math.ceil(total / 100000) * 100000
  // ];

  // const unique = [...new Set(denominations)]
  //   .filter(d => d >= total)
  //   .slice(0, 4);

  // qc.innerHTML = unique.map(d =>
  //   `<button class="quick-cash-btn" onclick="setQuickCash(${d})">
  //     Rp ${fmtNum(d)}
  //   </button>`
  // ).join('');

  calcChange();
}

function setQuickCash(amount) {
  document.getElementById('amountPaid').value = amount;
  calcChange();
}

function calcChange() {
  const subtotal = cart.reduce((s, i) => s + i.subtotal, 0);

  const total = subtotal * (1 + TAX_PERCENT / 100);

  const paid =
    parseFloat(document.getElementById('amountPaid').value) || 0;

  const change = paid - total;

  document.getElementById('changeDisplay').textContent =
    'Rp ' + fmtNum(Math.max(0, change));

  document.getElementById('changeDisplay').style.color =
    change >= 0 ? '#10b981' : '#ef4444';
}

function selectPayment(pm, btn) {
  selectedPayment = pm;
  document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('cashSection').style.display = pm === 'cash' ? 'block' : 'none';
  if (pm !== 'cash') {
    const total = parseFloat(document.getElementById('totalDisplay').textContent.replace(/[^0-9]/g,''));
    document.getElementById('amountPaid').value = total;
  }
}

// Checkout
async function checkout() {
  if (cart.length === 0) { showToast('Keranjang kosong!', 'error'); return; }

const subtotal = cart.reduce((s, i) => s + i.subtotal, 0);

const total = subtotal * (1 + TAX_PERCENT / 100);

const amountPaid =
  parseFloat(document.getElementById('amountPaid').value) ||
  (selectedPayment !== 'cash' ? total : 0);

  if (selectedPayment === 'cash' && amountPaid < total) {
    showToast('⚠️ Uang kurang dari total!', 'error'); return;
  }

  const btn = document.getElementById('checkoutBtn');
  btn.disabled = true; btn.textContent = '⏳ Memproses...';

  try {
    const res = await fetch('{{ route("cashier.checkout") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({
        items: cart.map(i => ({ product_id: i.id, quantity: i.qty, discount: 0 })),
        customer_name: document.getElementById('customerName').value,
        customer_table_queue: document.getElementById('customerTableQueue').value,
        discount_amount: 0,
        discount_percent: 0,
        tax_percent: TAX_PERCENT,
        payment_method: selectedPayment,
        amount_paid: amountPaid,
      })
    });
    const data = await res.json();
    if (data.success) {
      lastTransaction = data;
      showReceipt(data);
    } else {
      showToast('❌ ' + (data.message || 'Gagal checkout'), 'error');
      btn.disabled = false; btn.textContent = '✅ Bayar Sekarang';
    }
  } catch(e) {
    showToast('❌ Terjadi kesalahan koneksi', 'error');
    btn.disabled = false; btn.textContent = '✅ Bayar Sekarang';
  }
}

function showReceipt(data) {
  const trx = data.transaction;
  document.getElementById('rcStoreName').textContent = data.store_name;
  document.getElementById('rcStoreAddress').textContent = data.store_address;

  const pm = { cash:'Tunai', debit:'Debit', credit:'Kredit', qris:'QRIS', transfer:'Transfer' };
  let html = `
    <div class="receipt-row"><span>${trx.invoice_number}</span><span>${new Date(trx.created_at).toLocaleDateString('id-ID')}</span></div>
    <div class="receipt-row"><span>Kasir: ${trx.user?.name||'-'}</span><span>${new Date(trx.created_at).toLocaleTimeString('id-ID')}</span></div>
    ${trx.customer_name ? `<div class="receipt-row"><span>Pelanggan: ${trx.customer_name}</span></div>` : ''}
    <hr class="receipt-div">
    ${trx.customer_phone ? `<div class="receipt-row" style="font-weight: bold;"><span>Meja / Antrean: ${trx.customer_phone}</span></div>` : ''}
    ${trx.items.map(item=>`
      <div style="margin-bottom:5px;">
        <div>${item.product_name}</div>
        <div class="receipt-row"><span>${item.quantity} x Rp ${fmtNum(item.price)}</span><span>Rp ${fmtNum(item.subtotal)}</span></div>
      </div>
    `).join('')}
    <hr class="receipt-div">
    <div class="receipt-row"><span>Subtotal</span><span>Rp ${fmtNum(trx.subtotal)}</span></div>
    ${trx.discount_amount>0?`<div class="receipt-row"><span>Diskon</span><span>- Rp ${fmtNum(trx.discount_amount)}</span></div>`:''}
    ${trx.tax_amount>0?`<div class="receipt-row"><span>Pajak</span><span>Rp ${fmtNum(trx.tax_amount)}</span></div>`:''}
    <hr class="receipt-div">
    <div class="receipt-row receipt-total"><span>TOTAL</span><span>Rp ${fmtNum(trx.total)}</span></div>
    <div class="receipt-row"><span>${pm[trx.payment_method]||trx.payment_method}</span><span>Rp ${fmtNum(trx.amount_paid)}</span></div>
    <div class="receipt-row"><span>Kembalian</span><span>Rp ${fmtNum(trx.change_amount)}</span></div>
    <hr class="receipt-div">
    <div style="text-align:center;color:#94a3b8;font-size:12px;">${data.receipt_footer||'Terima kasih!'}</div>
  `;
  document.getElementById('receiptBody').innerHTML = html;
  document.getElementById('receiptModal').classList.add('open');
}

function printReceipt() {
  // Tarik scroll modal ke posisi paling atas sebelum print dimulai
  const bodyBox = document.getElementById('receiptBody');
  if (bodyBox) bodyBox.scrollTop = 0;
  
  window.print();
}

function newTransaction() {
  cart = []; selectedPayment = 'cash';
  renderCart();
  document.getElementById('customerName').value = '';
  document.getElementById('customerTableQueue').value = '';
  document.getElementById('amountPaid').value = '';
  document.getElementById('cashSection').style.display = 'block';
  document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('active'));
  document.querySelector('[data-pm="cash"]').classList.add('active');
  document.getElementById('receiptModal').classList.remove('open');
  document.getElementById('checkoutBtn').disabled = true;
  document.getElementById('checkoutBtn').textContent = '✅ Bayar Sekarang';

  // refresh product stocks
  fetch('{{ route("cashier.products") }}').then(r=>r.json()).then(products=>{
    products.forEach(p => {
      const card = document.querySelector(`.product-card[data-id="${p.id}"]`);
      if (card) {
        card.dataset.stock = p.stock;
        card.querySelector('.product-stock').textContent = `Stok: ${p.stock} ${p.unit}`;
        if (p.stock <= 0) card.classList.add('out-of-stock');
        else card.classList.remove('out-of-stock');
      }
    });
    allProducts = Array.from(document.querySelectorAll('.product-card'));
  });

  showToast('✅ Siap transaksi baru!', 'success');
}

// Init
renderCart();
document.getElementById('cashSection').style.display = 'block';
</script>
</body>
</html>
