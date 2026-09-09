<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem STTP Ditreskrimsus Polda Sumsel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-secondary: #0f172a;
            --sidebar-width: 265px;
            --header-height: 70px;
        }

        [data-bs-theme="dark"] {
            --bg-body: #090d16;
            --bg-card: rgba(15, 23, 42, 0.85);
            --bg-sidebar: #0a0f1d;
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --input-bg: rgba(15, 23, 42, 0.6);
            --shadow-custom: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        [data-bs-theme="light"] {
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --bg-sidebar: #0f172a; /* Keep dark sidebar for professional contrast */
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --input-bg: #ffffff;
            --shadow-custom: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .app-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.5);
        }

        .brand-text-main {
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-text-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 1.25rem 0.85rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-header {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            padding: 0.5rem 0.85rem;
            margin-top: 0.75rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
        }

        .nav-item-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(3px);
        }

        .nav-item-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .nav-item-link i {
            font-size: 1.15rem;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 10px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
        }

        /* Top Navbar */
        .app-header {
            height: var(--header-height);
            margin-left: var(--sidebar-width);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(10px);
        }

        /* Main Content */
        .app-main {
            margin-left: var(--sidebar-width);
            padding: 1.75rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Card Styles */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-custom);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .card-custom {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            box-shadow: var(--shadow-custom);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Responsive Mobile Layout */
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }
            .app-sidebar.show {
                transform: translateX(0);
            }
            .app-header, .app-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="app-sidebar" id="sidebar">
        <div class="sidebar-brand">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo Polda" style="height: 44px; width: auto;" class="me-1">
            @elseif(file_exists(public_path('images/logo1.png')))
                <img src="{{ asset('images/logo1.png') }}" alt="Logo Polda" style="height: 44px; width: auto;" class="me-1">
            @else
                <div class="brand-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            @endif
            <div>
                <div class="brand-text-main">STTP CYBER</div>
                <div class="brand-text-sub">Ditreskrimsus Polda Sumsel</div>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-header">Menu Utama</div>
            
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="nav-item-link {{ request()->routeIs('dashboard') || request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard Admin</span>
                </a>
            @else
                <a href="{{ route('dashboard.petugas.home') }}" class="nav-item-link {{ request()->routeIs('dashboard.petugas.home') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('dashboard.petugas') }}" class="nav-item-link {{ request()->routeIs('dashboard.petugas') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-plus-fill"></i>
                    <span>Buat STTP Baru</span>
                </a>
            @endif

            <a href="{{ route('reports.index') }}" class="nav-item-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat STTP</span>
            </a>

            <div class="menu-header">Pengaturan</div>
            <a href="javascript:void(0)" id="themeToggleBtn" class="nav-item-link">
                <i class="bi bi-moon-stars-fill" id="themeToggleIcon"></i>
                <span id="themeToggleText">Mode Terang</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="user-pill mb-2">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-white text-truncate" style="font-size: 0.88rem;">{{ Auth::user()->name }}</div>
                    <div class="badge {{ Auth::user()->role === 'admin' ? 'bg-danger' : 'bg-primary' }} text-uppercase" style="font-size: 0.65rem;">
                        {{ Auth::user()->role }}
                    </div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Top Header -->
    <header class="app-header">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-link link-body-emphasis d-lg-none p-0 text-decoration-none" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-2"></i>
            </button>
            <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="app-main">
        <!-- Toast Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Theme Switcher Logic
        const htmlTag = document.documentElement;
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeToggleIcon = document.getElementById('themeToggleIcon');
        const themeToggleText = document.getElementById('themeToggleText');

        function applyTheme(theme) {
            htmlTag.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            if (theme === 'dark') {
                themeToggleIcon.className = 'bi bi-sun-fill text-warning';
                themeToggleText.textContent = 'Mode Terang';
            } else {
                themeToggleIcon.className = 'bi bi-moon-stars-fill text-primary';
                themeToggleText.textContent = 'Mode Gelap';
            }
        }

        const savedTheme = localStorage.getItem('theme') || 'dark';
        applyTheme(savedTheme);

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = htmlTag.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });

        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
