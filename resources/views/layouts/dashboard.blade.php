<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Host Dashboard') — CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #F7F3ED; }
        .dashboard-layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ─────────────────────────────────────────── */
        .sidebar {
            width: 210px;
            background: #1A2E1C;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 50;
            transition: width 0.25s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;
        }
        .sidebar.collapsed { width: 64px; }

        /* Logo area */
        .sidebar-logo {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 68px;
            flex-shrink: 0;
        }
        .sidebar-logo-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.12);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logo-text { overflow: hidden; white-space: nowrap; }
        .sidebar-logo-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            letter-spacing: 0.02em;
        }
        .sidebar-logo-sub {
            font-size: 0.62rem;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.1rem;
        }

        /* Nav links */
        .sidebar-nav {
            flex: 1;
            padding: 0.875rem 0.625rem;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 9px; }

        .nav-label {
            font-size: 0.6rem;
            font-weight: 700;
            color: rgba(255,255,255,0.25);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0 0.75rem;
            margin: 0.75rem 0 0.4rem;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }
        .sidebar.collapsed .nav-label { opacity: 0; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            font-size: 0.83rem;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            margin-bottom: 0.15rem;
            transition: all 0.15s;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }
        .sidebar-nav a:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar-nav a.active { background: rgba(255,255,255,0.13); color: white; }
        .sidebar-nav a svg { flex-shrink: 0; opacity: 0.65; transition: opacity 0.15s; }
        .sidebar-nav a:hover svg, .sidebar-nav a.active svg { opacity: 1; }
        .sidebar-nav a .link-text { transition: opacity 0.2s; }
        .sidebar.collapsed .sidebar-nav a .link-text { opacity: 0; }

        /* Bottom */
        .sidebar-bottom {
            padding: 0.625rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .sidebar-bottom a,
        .sidebar-bottom button.logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 0.1rem;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
        }
        .sidebar-bottom a:hover,
        .sidebar-bottom button.logout-btn:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar-bottom a svg,
        .sidebar-bottom button.logout-btn svg { flex-shrink: 0; opacity: 0.65; }
        .sidebar-bottom a:hover svg,
        .sidebar-bottom button.logout-btn:hover svg { opacity: 1; }
        .sidebar-bottom .link-text { transition: opacity 0.2s; }
        .sidebar.collapsed .sidebar-bottom .link-text { opacity: 0; }

        /* Tooltip for sidebar-bottom items when collapsed */
        .sidebar.collapsed .sidebar-bottom a,
        .sidebar.collapsed .sidebar-bottom button.logout-btn {
            position: relative;
        }

        /* ── Main Content ─────────────────────────────────────── */
        .main-content {
            margin-left: 210px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .main-content.sidebar-collapsed { margin-left: 64px; }

        /* Top Header */
        .top-header {
            background: white;
            border-bottom: 1px solid #EDE7DC;
            padding: 0.875rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
            gap: 1rem;
        }
        .top-header-left { display: flex; align-items: center; gap: 0.875rem; }
        .top-header-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 500;
            color: #1E3A2F;
            white-space: nowrap;
        }
        .top-header-right {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }
        .header-search {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #F7F3ED;
            border: 1.5px solid #EDE7DC;
            border-radius: 8px;
            padding: 0.5rem 0.875rem;
        }
        .header-search input {
            background: none;
            border: none;
            outline: none;
            font-size: 0.82rem;
            color: #1E3A2F;
            width: 180px;
            font-family: 'DM Sans', sans-serif;
        }
        .header-search input::placeholder { color: #9CA3AF; }
        .header-icon-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1.5px solid #EDE7DC;
            background: white;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            color: #7A7A6E;
            flex-shrink: 0;
        }
        .header-icon-btn:hover { background: #F7F3ED; color: #1E3A2F; }
        .host-avatar {
            width: 36px; height: 36px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #EDE7DC;
        }

        /* Page Content */
        .page-content { padding: 2rem; flex: 1; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

<div class="dashboard-layout"
     x-data="{
        collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar_collapsed', this.collapsed);
        }
     }">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="sidebar" :class="{ 'collapsed': collapsed }">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="2" stroke-linecap="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="sidebar-logo-text">
                <div class="sidebar-logo-title">Host Portal</div>
                <div class="sidebar-logo-sub">CittaLoka</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav">

            <div class="nav-label">Main</div>

            <a href="{{ route('host.dashboard') }}"
               class="{{ request()->routeIs('host.dashboard') ? 'active' : '' }}"
               data-label="Dashboard">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                <span class="link-text">Dashboard</span>
            </a>

            <a href="{{ route('host.experiences.index') }}"
               class="{{ request()->routeIs('host.experiences.*') ? 'active' : '' }}"
               data-label="My Experiences">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                <span class="link-text">My Experiences</span>
            </a>

            <a href="{{ route('host.bookings.index') }}"
               class="{{ request()->routeIs('host.bookings.*') ? 'active' : '' }}"
               data-label="Bookings">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span class="link-text">Bookings</span>
            </a>

            <a href="{{ route('host.availability.index') }}"
               class="{{ request()->routeIs('host.availability.*') ? 'active' : '' }}"
               data-label="Availability">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="link-text">Availability</span>
            </a>

            <a href="{{ route('host.memory-books.index') }}"
               class="{{ request()->routeIs('host.memory-books.*') ? 'active' : '' }}"
               data-label="Memory Books">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span class="link-text">Memory Books</span>
            </a>

            <div class="nav-label">Finance</div>

            <a href="{{ route('host.earnings') }}"
               class="{{ request()->routeIs('host.earnings') ? 'active' : '' }}"
               data-label="Earnings">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="link-text">Earnings</span>
            </a>

            <div class="nav-label">Help</div>

            <a href="#"
               class="{{ request()->routeIs('host.support') ? 'active' : '' }}"
               data-label="Support">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span class="link-text">Support</span>
            </a>

        </nav>

        {{-- Bottom --}}
        <div class="sidebar-bottom">
            <a href="{{ route('host.settings') }}"
               class="{{ request()->routeIs('host.settings') ? 'active' : '' }}"
               data-label="Settings">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span class="link-text">Settings</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" data-label="Logout">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span class="link-text">Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="main-content" :class="{ 'sidebar-collapsed': collapsed }">

        {{-- Top Header --}}
        <header class="top-header">
            <div class="top-header-left">
                {{-- Hamburger toggle (visible on header) --}}
                <button class="header-icon-btn" @click="toggle()" :title="collapsed ? 'Buka sidebar' : 'Tutup sidebar'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div class="top-header-title">@yield('page-title', 'Dashboard')</div>
            </div>

            <div class="top-header-right">
                {{-- Search --}}
                <div class="header-search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" placeholder="Search experiences...">
                </div>
                {{-- Notif --}}
                <a href="#" class="header-icon-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </a>
                {{-- Settings --}}
                <a href="{{ route('host.settings') }}" class="header-icon-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </a>
                {{-- Avatar --}}
                <img src="{{ auth()->user()->avatar ?? "https://ui-avatars.com/api/?name=" . urlencode(auth()->user()->name) . "&background=1E3A2F&color=fff&size=64" }}" alt="" class="host-avatar">
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
