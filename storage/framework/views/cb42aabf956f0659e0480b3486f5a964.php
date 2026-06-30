<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — POS System</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand: #0f172a;
            --accent: #6366f1;
            --accent2: #f59e0b;
            --surface: #ffffff;
            --muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            top: -100px; left: -100px;
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, transparent 70%);
            bottom: -50px; right: -50px;
            border-radius: 50%;
        }

        .brand-logo {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-bottom: 60px;
        }

        .logo-icon {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-size: 36px;
            box-shadow: 0 20px 60px rgba(99,102,241,0.3);
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            color: #f8fafc;
            font-size: 36px;
            letter-spacing: -0.5px;
        }

        .brand-sub {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 8px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .features {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 340px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .feature-text h4 { color: #f1f5f9; font-size: 14px; font-weight: 600; }
        .feature-text p { color: #64748b; font-size: 12px; margin-top: 2px; }

        /* Right Panel */
        .right-panel {
            width: 480px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #64748b;
            margin-top: 8px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
        }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            color: #0f172a;
            transition: all 0.2s;
            outline: none;
            background: #f8fafc;
        }

        input:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            background: none;
            border: none;
            font-size: 18px;
        }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(99,102,241,0.35);
        }

        .btn-login:active { transform: translateY(0); }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            color: #64748b;
            font-weight: 400;
            cursor: pointer;
            font-size: 14px;
        }

        .hint-box {
            margin-top: 32px;
            padding: 16px;
            background: #f0f9ff;
            border-radius: 12px;
            border: 1px solid #bae6fd;
        }

        .hint-box p { font-size: 12px; color: #0369a1; font-weight: 500; }
        .hint-box code { background: #dbeafe; padding: 1px 6px; border-radius: 4px; font-size: 12px; }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 30px; }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="brand-logo">
            <div class="logo-icon">🏪</div>
            <h1 class="brand-name">Alifian</h1>
            <p class="brand-sub">Point of Sale System</p>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon" style="background: rgba(99,102,241,0.2)">🛒</div>
                <div class="feature-text">
                    <h4>Kasir Cepat & Mudah</h4>
                    <p>Proses transaksi dalam hitungan detik</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background: rgba(245,158,11,0.2)">📊</div>
                <div class="feature-text">
                    <h4>Laporan Komprehensif</h4>
                    <p>Pantau omzet harian, mingguan & bulanan</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background: rgba(16,185,129,0.2)">📦</div>
                <div class="feature-text">
                    <h4>Manajemen Stok</h4>
                    <p>Kontrol inventori dengan notifikasi otomatis</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background: rgba(239,68,68,0.2)">👥</div>
                <div class="feature-text">
                    <h4>Multi Pengguna</h4>
                    <p>Admin & kasir dengan hak akses berbeda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="login-header">
            <h1>Selamat datang 👋</h1>
            <p>Masuk ke sistem kasir Anda</p>
        </div>

        <?php if($errors->any()): ?>
        <div class="error-msg">
            ⚠️ <?php echo e($errors->first()); ?>

        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('login.submit')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Alamat Email</label>
                <div class="input-wrap">
                    <span class="input-icon">✉️</span>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="admin@pos.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">Masuk ke Sistem →</button>
        </form>

        <div class="hint-box">
            <p>🔑 <strong>Demo Login:</strong><br>
            Admin: <code>admin@pos.com</code> / <code>password</code><br>
            Kasir: <code>kasir@pos.com</code> / <code>password</code></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html> -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffee aL </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #ffffff;
            color: #1e293b;
        }

        /* --- SISI KIRI: VISUAL & BRANDING --- */
        .left-side {
            flex: 1.2;
            position: relative;
            /* Gradasi warna gelap ke moka hangat khas kopi kekinian */
            background:radial-gradient(circle at top right,rgba(212,175,55,.18),transparent 35%),linear-gradient(135deg, #2B1E1A 0%, #4A2C24 45%, #6F4E37 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            overflow: hidden;
        }

        /* Efek dekorasi cahaya abstrak di background */
        .left-side::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .brand-section h1 {
            color: #ffffff;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-section h1 span {
            color:#D4AF37; /* Aksen ungu muda */
        }

        /* Preview Elemen Transparan (Glassmorphism) Menggantikan Kotak Fitur Lama */
        .preview-container {
            position: relative;
            z-index: 2;
            margin: 40px 0;
        }

        .glass-card {
            background:rgba(255,255,255,.08);
            backdrop-filter:blur(18px);
            -webkit-backdrop-filter: blur(12px);
            border:1px solid rgba(255,255,255,.15);
            border-radius: 28px;
            padding: 35px;
            box-shadow:0 25px 60px rgba(0,0,0,.35);
        }

        .glass-card h2 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .glass-card p {
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        /* Simulasi grafik/status mini di dalam kartu kaca biar makin estetik */
        .mock-stats {
            display: flex;
            gap: 15px;
        }

        .stat-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-box .label {
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-box .value {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            margin-top: 4px;
        }

        .left-footer {
            color: #94a3b8;
            font-size: 12px;
            z-index: 2;
        }


        /* --- SISI KANAN: FORM LOGIN CLEAN AESTHETIC --- */
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #ffffff;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #94a3b8;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border:1px solid #E5D8CB;
            background:#FCFBF8;
            border-radius: 12px;
            font-size: 14px;
            color: #0f172a;
            transition: all 0.3s ease;
        }

        /* Efek glow halus warna ungu saat input diklik */
        .form-control:focus {
            outline: none;
            border-color:#8B5E3C;
            box-shadow:0 0 0 4px rgba(139,94,60,.15);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            cursor: pointer;
        }

        .remember-me input {
            accent-color: #7c3aed;
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background:linear-gradient(90deg,#8B5E3C,#C08A5C);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow:0 12px 30px rgba(139,94,60,.3);

        }

        .btn-login:hover {
            background:linear-gradient(90deg,#9D6B45,#D39B6A);
            transform:translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.35);
        }

        /* Akun Demo Box yang Lebih Rapi */
        .demo-account-box {
            margin-top: 35px;
            background:#FFFDF9;
            border:1px solid #EADCCF;
            border-radius: 12px;
            padding: 16px;
        }

        .demo-title {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .demo-credentials {
            font-size: 13px;
            color: #334155;
            line-height: 1.6;
        }

        .demo-credentials code {
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }

        /* Responsif untuk layar HP/Tablet kecil */
        @media (max-width: 900px) {
            .left-side {
                display: none; /* Sembunyikan sisi kiri di layar HP */
            }
        }
    </style>
</head>
<body>

    <div class="left-side">
        <div class="brand-section">
            <h1>Coffee<span>aL.</span></h1>
        </div>

        <div class="preview-container">
            <div class="glass-card">
                <h2>Alifian Java Script Enthusiast</h2>
                <p>Belum jadi ini njir web, msh banyak yg harus diperbaiki dan terlalu basic buat tampilannya, males njir ngerjainnya karna pake PHP mending JS</p>
                
                <div class="mock-stats">
                    <div class="stat-box">
                        <div class="label">Omzet Hari Ini</div>
                        <div class="value">Rp 2.450k</div>
                    </div>
                    <div class="stat-box">
                        <div class="label">Cup Terjual</div>
                        <div class="value">142 Gelas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="left-footer">
            <p>&copy; 2026 Coffee aL POS System. All rights reserved.</p>
        </div>
    </div>

    <div class="right-side">
        <div class="login-box">
            <div class="login-header">
                <h2>Selamat datang 👋</h2>
                <p>Masuk ke sistem untuk mulai melayani pelanggan.</p>
            </div>

            <form action="<?php echo e(route('login.submit')); ?>" method="POST">
            <?php echo csrf_field(); ?>

                <?php if($errors->any()): ?>
                    <div style="color: #ef4444; font-size: 13px; margin-bottom: 20px; background: #fee2e2; padding: 12px; border-radius: 10px; border: 1px solid #fca5a5;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="margin-bottom: 2px;">⚠️ <?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Alamat Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input type="email" name="email" class="form-control" placeholder="alifian@gmail.com" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••••••" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-login">Masuk ke Sistem →</button>
            </form>

            <div class="demo-account-box">
                <div class="demo-title">🔑 Akun Akses Demo:</div>
                <div class="demo-credentials">
                    <div>Admin: <code>admin@pos.com</code> / <code>password</code></div>
                    <div style="margin-top: 4px;">Kasir: <code>kasir@pos.com</code> / <code>password</code></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH D:\Alifiann\Project Full Stack\pos-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>