@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name . '! 👋')

@section('styles')
<style>
.grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.chart-bar-wrap { display: flex; align-items: flex-end; gap: 8px; height: 160px; }
.chart-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; justify-content: flex-end; }
.chart-bar { width: 100%; background: linear-gradient(to top, #6366f1, #818cf8); border-radius: 6px 6px 0 0; min-height: 4px; transition: height 0.5s ease; position: relative; }
.chart-bar:hover { opacity: 0.8; cursor: pointer; }
.chart-bar-label { font-size: 11px; color: #94a3b8; }
.chart-bar-value { font-size: 10px; color: #6366f1; font-weight: 700; }
.donut-row { display: flex; flex-direction: column; gap: 10px; }
.donut-item { display: flex; align-items: center; gap: 10px; font-size: 13px; }
.donut-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.donut-label { flex: 1; color: #475569; }
.donut-val { font-weight: 700; color: #0f172a; font-size: 13px; }
.trend-up { color: #10b981; font-size: 12px; font-weight: 700; }
.trend-down { color: #ef4444; font-size: 12px; font-weight: 700; }
.low-stock-row td:first-child { font-weight: 600; }
.stock-bar { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; width: 80px; }
.stock-bar-fill { height: 100%; border-radius: 3px; }
.invoice-link { color: #6366f1; font-weight: 600; text-decoration: none; font-size: 13px; }
.invoice-link:hover { text-decoration: underline; }
@media (max-width: 1024px) { .grid-2 { grid-template-columns: 1fr; } .grid-3 { grid-template-columns: 1fr 1fr; } }
</style>
@endsection

@section('content')
<!-- Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #ede9fe;">💰</div>
        <div class="stat-info">
            <h3>Rp {{ number_format($stats['today_sales'], 0, ',', '.') }}</h3>
            <p>Penjualan Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe;">🧾</div>
        <div class="stat-info">
            <h3>{{ $stats['today_transactions'] }}</h3>
            <p>Transaksi Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #d1fae5;">📅</div>
        <div class="stat-info">
            <h3>Rp {{ number_format($stats['month_sales'], 0, ',', '.') }}</h3>
            <p>Penjualan Bulan Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7;">📦</div>
        <div class="stat-info">
            <h3>{{ $stats['total_products'] }}</h3>
            <p>Total Produk Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2;">⚠️</div>
        <div class="stat-info">
            <h3>{{ $stats['low_stock_products'] }}</h3>
            <p>Produk Stok Menipis</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f0fdf4;">🏷️</div>
        <div class="stat-info">
            <h3>{{ $stats['total_categories'] }}</h3>
            <p>Kategori Aktif</p>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 24px;">
    <!-- Sales Chart -->
    <div class="card">
        <div class="card-header">
            <h3>📈 Grafik Penjualan 7 Hari</h3>
        </div>
        <div class="card-body">
            @php $maxSales = collect($salesChart)->max('total') ?: 1; @endphp
            <div class="chart-bar-wrap" id="salesChart">
                @foreach($salesChart as $day)
                <div class="chart-bar-col">
                    <div class="chart-bar-value">{{ $day['total'] > 0 ? 'Rp ' . number_format($day['total']/1000, 0) . 'K' : '-' }}</div>
                    <div class="chart-bar" style="height: {{ max(4, ($day['total'] / $maxSales) * 130) }}px"
                         title="{{ $day['date'] }}: Rp {{ number_format($day['total'], 0, ',', '.') }}"></div>
                    <div class="chart-bar-label">{{ $day['date'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="card">
        <div class="card-header">
            <h3>💳 Metode Pembayaran</h3>
            <span style="font-size: 12px; color: #94a3b8;">Bulan ini</span>
        </div>
        <div class="card-body">
            @php
            $colors = ['cash'=>'#10b981','debit'=>'#3b82f6','credit'=>'#8b5cf6','qris'=>'#f59e0b','transfer'=>'#06b6d4'];
            $labels = ['cash'=>'Tunai','debit'=>'Debit','credit'=>'Kredit','qris'=>'QRIS','transfer'=>'Transfer'];
            $totalPayment = $paymentBreakdown->sum('total') ?: 1;
            @endphp
            <div class="donut-row">
                @forelse($paymentBreakdown as $p)
                <div class="donut-item">
                    <div class="donut-dot" style="background: {{ $colors[$p->payment_method] ?? '#94a3b8' }}"></div>
                    <span class="donut-label">{{ $labels[$p->payment_method] ?? $p->payment_method }} ({{ $p->count }}x)</span>
                    <span class="donut-val">Rp {{ number_format($p->total/1000, 0) }}K</span>
                </div>
                <div style="height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                    <div style="height: 100%; width: {{ ($p->total/$totalPayment)*100 }}%; background: {{ $colors[$p->payment_method] ?? '#94a3b8' }}; border-radius: 3px;"></div>
                </div>
                @empty
                <p style="color: #94a3b8; font-size: 14px; text-align: center; padding: 20px 0;">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 24px;">
    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <h3>🧾 Transaksi Terakhir</h3>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                    <tr>
                        <td><a href="{{ route('admin.transactions.show', $trx) }}" class="invoice-link">{{ $trx->invoice_number }}</a></td>
                        <td>{{ $trx->user->name ?? '-' }}</td>
                        <td><strong>Rp {{ number_format($trx->total, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($trx->status === 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($trx->status === 'cancelled')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td style="color: #94a3b8; font-size: 12px;">{{ $trx->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; color:#94a3b8; padding: 24px;">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock & Top Products -->
    <div style="display:flex; flex-direction:column; gap:20px;">
        <div class="card">
            <div class="card-header">
                <h3>⚠️ Stok Menipis</h3>
                <a href="{{ route('admin.products.index', ['status'=>'low_stock']) }}" class="btn btn-secondary btn-sm">Kelola</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Produk</th><th>Stok</th><th>Min</th></tr></thead>
                    <tbody>
                        @forelse($lowStockProducts->take(5) as $p)
                        <tr class="low-stock-row">
                            <td>{{ Str::limit($p->name, 25) }}</td>
                            <td>
                                <span style="color: {{ $p->stock == 0 ? '#ef4444' : '#f59e0b' }}; font-weight:700;">{{ $p->stock }}</span>
                            </td>
                            <td style="color:#94a3b8;">{{ $p->min_stock }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:16px;">Semua stok aman ✅</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>🏆 Top Produk Bulan Ini</h3>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Produk</th><th>Qty</th><th>Omzet</th></tr></thead>
                    <tbody>
                        @forelse($topProducts as $p)
                        <tr>
                            <td>{{ Str::limit($p->name, 22) }}</td>
                            <td><span class="badge badge-purple">{{ $p->total_qty }}</span></td>
                            <td style="font-size:12px; font-weight:600; color:#6366f1;">Rp {{ number_format($p->total_revenue/1000, 0) }}K</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:16px;">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
