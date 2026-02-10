<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Elektron</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            background: #e8e1e1;
            display: flex;
            transition: background 0.3s;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 220px;
            background: linear-gradient(180deg, #4b1b8c, #3a0f6d);
            color: white;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar h2 {
            margin: 20px 0 40px;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 1px;
            text-align: center;
        }

        .sidebar a {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.15);
            transition: background 0.2s;
            text-align: center;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.3);
        }

        .sidebar a.active {
            background: rgba(255,255,255,0.35);
            font-weight: 600;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,.1);
            transition: background 0.3s, color 0.3s, box-shadow 0.3s;
        }

        /* ===== MINI TOGGLE ===== */
        .theme-toggle {
            position: fixed;
            right: 18px;
            bottom: 18px;
            width: 42px;
            height: 22px;
            background: #d1c4e9;
            border-radius: 50px;
            cursor: pointer;
            transition: background 0.3s;
            z-index: 999;
        }

        .theme-toggle::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        /* ===== DARK MODE ===== */
        body.dark {
            background: #121212;
        }

        body.dark .sidebar {
            background: linear-gradient(180deg, #1c1c1c, #111);
        }

        body.dark .sidebar a {
            background: rgba(255,255,255,0.08);
        }

        body.dark .sidebar a.active,
        body.dark .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }

        body.dark .card {
            background: #1e1e1e;
            color: #e0e0e0;
            box-shadow: 0 5px 15px rgba(0,0,0,.6);
        }

        body.dark th { color: #aaa; }
        body.dark td { color: #ddd; border-bottom: 1px solid #333; }

        body.dark .theme-toggle {
            background: #7b4ce2;
        }

        body.dark .theme-toggle::after {
            transform: translateX(20px);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Elektron</h2>

    <a href="/admin/dashboard" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="/admin/kategori" class="{{ Request::is('admin/kategori*') ? 'active' : '' }}">Kategori</a>
    <a href="/admin/alat" class="{{ Request::is('admin/alat*') ? 'active' : '' }}">Alat</a>
    <a href="/admin/pelanggan" class="{{ Request::is('admin/pelanggan*') ? 'active' : '' }}">Pelanggan</a>
    <a href="/admin/penyewaan" class="{{ Request::is('admin/penyewaan*') ? 'active' : '' }}">Penyewaan</a>

    <!-- Logout -->
    <a href="/admin/logout">Logout</a>
</div>

<div class="content">
    @yield('content')
</div>

<!-- TOGGLE -->
<div id="themeToggle" class="theme-toggle"></div>

<script>
    // ===== Dark/Light Toggle =====
    const toggle = document.getElementById('themeToggle');
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark');
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem(
            'theme',
            document.body.classList.contains('dark') ? 'dark' : 'light'
        );
    });

    // ===== Logout Redirect =====
    document.addEventListener('DOMContentLoaded', () => {
        const logoutLink = document.querySelector('.sidebar a[href="/admin/logout"]');

        if(logoutLink){
            logoutLink.addEventListener('click', function(e){
                e.preventDefault(); // cegah default

                // Hapus token JWT jika ada
                localStorage.removeItem('admin_token');

                // Redirect ke halaman login/admin
                window.location.href = '/admin/login'; // ubah sesuai halaman login admin
            });
        }
    });
</script>

</body>
</html>
