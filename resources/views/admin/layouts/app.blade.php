<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Faradisa HomeStay</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --sidebar-bg: #0f172a;
            --main-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
        }

        .sidebar-brand-text {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .sidebar-menu {
            padding: 1.25rem 0.75rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-category {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 0.75rem 0.35rem 0.75rem;
            margin-top: 0.5rem;
        }

        .nav-item {
            list-style: none;
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(99, 102, 241, 0.15);
            color: #818cf8;
        }

        .nav-link.active {
            font-weight: 600;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header Navbar */
        .topbar {
            height: 70px;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 12px;
            background-color: #f1f5f9;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .user-details {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .badge-role {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.1rem 0.4rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.68rem;
            text-transform: uppercase;
        }

        .btn-logout {
            background-color: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 0.55rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #fca5a5;
            color: #991b1b;
        }

        /* Page Content Area */
        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        /* Footer */
        .footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid var(--border-color);
            background-color: var(--card-bg);
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fa-solid fa-house-chimney"></i>
            </div>
            <div class="sidebar-brand-text">Faradisa Admin</div>
        </div>

        <ul class="sidebar-menu">
            <div class="menu-category"> Utama </div>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <div class="menu-category"> Pengelolaan </div>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-bed"></i>
                    <span>Kamar & Unit</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Pemesanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    <span>Data Tamu</span>
                </a>
            </li>

            <div class="menu-category"> Sistem </div>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="topbar">
            <h1 class="topbar-title">@yield('page_title', 'Dashboard Overview')</h1>

            <div class="topbar-actions">
                <div class="user-profile">
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name ?? 'Admin User' }}</span>
                        <span class="user-details">
                            @ {{ Auth::user()->username ?? 'admin' }} 
                            <span class="badge-role">{{ Auth::user()->role_user ?? 'Staff' }}</span>
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="content-area">
            @if(session('success'))
                <div style="background-color: #dcfce7; color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            &copy; {{ date('Y') }} Faradisa HomeStay - Panel Kontrol Admin.
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
