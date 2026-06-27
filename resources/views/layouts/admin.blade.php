<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — POSMaster</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --brand: #0f172a;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --surface: #ffffff;
            --bg: #f1f5f9;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        body { font-family: 'Space Grotesk', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--brand);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s;
        }

        .sidebar-logo {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        .sidebar-logo-text { color: #f8fafc; font-size: 18px; font-weight: 700; }
        .sidebar-logo-sub { color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; }

        .sidebar-user {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #f59e0b);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: white;
        }

        .user-info h4 { color: #f1f5f9; font-size: 13px; font-weight: 600; }
        .user-info span { color: #64748b; font-size: 11px; }
        .role-badge {
            margin-left: auto;
            padding: 2px 8px;
            background: rgba(99,102,241,0.2);
            color: #a5b4fc;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .nav-link:hover { background: rgba(255,255,255,0.06); color: #f1f5f9; }
        .nav-link.active { background: rgba(99,102,241,0.2); color: #818cf8; }
        .nav-link.active .nav-icon { filter: none; }

        .nav-icon { font-size: 18px; width: 20px; text-align: center; }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 1px 6px;
            border-radius: 10px;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: none;
            border-radius: 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-logout:hover { background: rgba(239,68,68,0.2); }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top bar */
        .topbar {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title h1 { font-size: 20px; font-weight: 700; color: var(--text); }
        .topbar-title p { font-size: 13px; color: var(--muted); margin-top: 2px; }

        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; border-radius: 10px; font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
        .btn-secondary { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-danger { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-success { background: #ecfdf5; color: var(--success); border: 1px solid #a7f3d0; }
        .btn-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* Page content */
        .page-content { padding: 32px; flex: 1; }

        /* Cards */
        .card { background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 15px; font-weight: 700; }
        .card-body { padding: 24px; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-info h3 { font-size: 22px; font-weight: 700; color: var(--text); }
        .stat-info p { font-size: 12px; color: var(--muted); margin-top: 2px; }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); background: #f8fafc; border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-purple { background: #ede9fe; color: #5b21b6; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        /* Alerts */
        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }
        .alert-error { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }

        /* Forms */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 0; }
        .form-group.full { grid-column: 1 / -1; }
        label.form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Space Grotesk', sans-serif; font-size: 14px; color: var(--text); transition: all 0.2s; outline: none; background: #f8fafc; }
        .form-control:focus { border-color: var(--accent); background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 80px; }

        /* Pagination */
        .pagination { display: flex; align-items: center; gap: 4px; margin-top: 20px; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; }
        .pagination a { background: white; border: 1px solid var(--border); color: var(--text); }
        .pagination a:hover { background: var(--bg); }
        .pagination .active { background: var(--accent); color: white; border-color: var(--accent); }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.open { display: flex; }
        .modal { background: white; border-radius: 20px; width: 90%; max-width: 560px; max-height: 90vh; overflow-y: auto; }
        .modal-header { padding: 24px 28px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-header h3 { font-size: 17px; font-weight: 700; }
        .modal-close { background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); }
        .modal-body { padding: 28px; }
        .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .form-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">🏪</div>
            <div>
                <div class="sidebar-logo-text">Coffee aL</div>
                <div class="sidebar-logo-sub">Admin Panel</div>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div class="user-info">
                <h4>{{ auth()->user()->name }}</h4>
                <span>{{ auth()->user()->email }}</span>
            </div>
            <span class="role-badge">{{ auth()->user()->role }}</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="{{ route('cashier.index') }}" class="nav-link">
                    <span class="nav-icon">🛒</span> Buka Kasir
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Produk</div>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <span class="nav-icon">📦</span> Produk
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <span class="nav-icon">🏷️</span> Kategori
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Penjualan</div>
                <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                    <span class="nav-icon">🧾</span> Transaksi
                </a>
                <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses*') ? 'active' : '' }}">
                    <span class="nav-icon">💸</span> Pengeluaran
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <span class="nav-icon">📈</span> Laporan
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Sistem</div>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> Pengguna
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <span class="nav-icon">⚙️</span> Pengaturan
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">🚪 Keluar</button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-title">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <p>@yield('page-subtitle', '')</p>
            </div>
            <div class="topbar-actions">
                @yield('topbar-actions')
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
