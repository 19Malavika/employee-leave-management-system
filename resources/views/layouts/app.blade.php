<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LeaveHQ') - Enterprise Leave Management</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary: #0f172a;       /* Charcoal */
            --primary-hover: #1e293b;
            --accent: #2563eb;        /* Royal Blue */
            --accent-hover: #1d4ed8;
            
            --sidebar-bg: #ffffff;
            --sidebar-border: #e2e8f0;
            --sidebar-text: #475569;
            --sidebar-text-active: #0f172a;
            
            --bg-app: #f8fafc;
            --surface: #ffffff;
            
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            
            --success-bg: #f0fdf4; --success-text: #15803d; --success-border: #bbf7d0;
            --warning-bg: #fffbeb; --warning-text: #b45309; --warning-border: #fde68a;
            --danger-bg: #fef2f2;  --danger-text: #b91c1c;  --danger-border: #fecaca;
            --info-bg: #eff6ff;    --info-text: #1d4ed8;    --info-border: #bfdbfe;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-app);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* -----------------------
           SIDEBAR (Light Theme)
        ------------------------ */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-header {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-icon {
            width: 28px;
            height: 28px;
            background: var(--accent);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .brand-text {
            color: var(--text-main);
            font-size: 16px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .sidebar-menu {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .menu-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            margin: 16px 0 8px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 6px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-link:hover {
            background-color: #f1f5f9;
            color: var(--sidebar-text-active);
        }

        .nav-link.active {
            background-color: #eff6ff;
            color: var(--accent);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            color: inherit;
        }

        /* -----------------------
           MAIN LAYOUT
        ------------------------ */
        .wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .header-search {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 6px;
            width: 280px;
            border: 1px solid transparent;
        }
        
        .header-search:focus-within {
            border-color: var(--accent);
            background: white;
            box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
        }

        .header-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            margin-left: 8px;
            color: var(--text-main);
        }

        .header-search svg {
            width: 16px;
            height: 16px;
            color: var(--text-muted);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            color: var(--text-muted);
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .notification-icon svg {
            width: 20px;
            height: 20px;
        }
        
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            border: 2px solid white;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 20px;
            border-left: 1px solid var(--border-light);
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f1f5f9;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid var(--border-light);
        }

        /* -----------------------
           PAGE CONTENT
        ------------------------ */
        .page-content {
            padding: 32px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            flex: 1;
        }

        /* Standardized Card */
        .card {
            background: var(--surface);
            border-radius: 8px;
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-light);
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: -0.2px;
        }

        /* Data Tables */
        .table-container {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        .data-table th {
            background: #f8fafc;
            padding: 12px 16px;
            font-weight: 500;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-light);
        }

        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-main);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.5;
            text-transform: capitalize;
        }

        .badge-pending { background: var(--warning-bg); color: var(--warning-text); border: 1px solid var(--warning-border); }
        .badge-approved { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border); }
        .badge-rejected { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-border); }
        .badge-cancelled { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
            line-height: 1.2;
        }

        .btn-primary { 
            background: var(--accent); 
            color: white; 
        }
        .btn-primary:hover { 
            background: var(--accent-hover); 
        }
        .btn-outline { 
            background: white; 
            border-color: var(--border-light); 
            color: var(--text-main); 
        }
        .btn-outline:hover { 
            background: #f8fafc; 
        }
        
        /* Typography overrides for headings */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        /* Inputs */
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            font-size: 13px;
            color: var(--text-main);
            background: white;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <div class="brand-icon">L</div>
                <div class="brand-text">LeaveHQ</div>
            </a>
        </div>

        @auth
        <nav class="sidebar-menu">
            <div class="menu-label">Overview</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <div class="menu-label">My Workspace</div>
            <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.index') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Leave Requests
            </a>
            <a href="{{ route('leaves.calendar') }}" class="nav-link {{ request()->routeIs('leaves.calendar') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Team Calendar
            </a>

            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
            <div class="menu-label">Management</div>
            <a href="{{ route('manager.leaves.index') }}" class="nav-link {{ request()->routeIs('manager.leaves.index') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Approvals
                @if(\App\Models\Leave::where('status', 'pending')->count() > 0)
                <span style="margin-left: auto; background: #ef4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: 600;">{{ \App\Models\Leave::where('status', 'pending')->count() }}</span>
                @endif
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <div class="menu-label">Administration</div>
            <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Directory
            </a>
            <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Departments
            </a>
            <a href="{{ route('admin.leave-types.index') }}" class="nav-link {{ request()->routeIs('admin.leave-types.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Leave Types
            </a>
            @endif
        </nav>
        @endauth
    </aside>

    <!-- Main Layout Wrapper -->
    <div class="wrapper">
        
        <!-- Top Navbar -->
        <header class="top-header">
            <!-- Global Search Mockup -->
            <div class="header-search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search employees, leaves, departments...">
            </div>

            @auth
            <div class="header-right">
                <!-- Notifications -->
                <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false">
                    <div class="notification-icon" @click="open = !open" style="cursor: pointer; position: relative;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span style="position: absolute; top: -2px; right: -2px; background: #ef4444; border: 2px solid white; width: 10px; height: 10px; border-radius: 50%;"></span>
                        @endif
                    </div>

                    <!-- Notification Dropdown -->
                    <div x-show="open" style="display: none; position: absolute; right: 0; top: 32px; width: 340px; background: white; border: 1px solid var(--border-light); border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); z-index: 50; overflow: hidden;">
                        <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                            <div style="font-weight: 600; font-size: 14px;">Notifications</div>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                    @csrf
                                    <button type="submit" style="font-size: 11px; color: var(--accent); background: none; border: none; cursor: pointer;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-light); display: flex; gap: 12px; align-items: flex-start; background: #eff6ff;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: white; border: 1px solid var(--border-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: {{ $notification->data['type'] === 'approved' ? '#16a34a' : ($notification->data['type'] === 'rejected' ? '#ef4444' : 'var(--accent)') }};">
                                        @if($notification->data['icon'] === 'check')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @elseif($notification->data['icon'] === 'x')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        @elseif($notification->data['icon'] === 'trash')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-size: 13px; color: var(--text-main); line-height: 1.4; margin-bottom: 4px;">
                                            {{ $notification->data['message'] }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between;">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                            <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}">
                                                @csrf
                                                <button type="submit" style="color: var(--accent); background: none; border: none; cursor: pointer; font-size: 11px;">Mark Read</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 24px 16px; text-align: center; color: var(--text-muted); font-size: 13px;">
                                    No new notifications 🎉
                                </div>
                            @endforelse
                        </div>
                        @if(auth()->user()->notifications()->count() > 0)
                        <div style="padding: 8px; text-align: center; border-top: 1px solid var(--border-light); background: #f8fafc;">
                            <a href="#" style="font-size: 12px; color: var(--text-muted); text-decoration: none;">View All Notifications</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- User Profile Section -->
                <div class="user-profile">
                    <div style="text-align: right; line-height: 1.2;">
                        <div style="font-size: 13px; font-weight: 500;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 11px; color: var(--text-muted); text-transform: capitalize;">{{ auth()->user()->role }}</div>
                    </div>
                    <div class="avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" style="margin-left: 8px;">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; font-weight: 500; border-color: transparent; box-shadow: none;">Logout</button>
                    </form>
                </div>
            </div>
            @endauth
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <!-- Toast Notifications (Professional Style) -->
    <style>
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: 1px solid var(--border-light);
            min-width: 280px;
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .toast-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .toast-success .toast-icon { color: #15803d; }
        .toast-error .toast-icon { color: #b91c1c; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

    <div class="toast-container" id="toast-container">
        @if(session('success'))
            <div class="toast toast-success" id="session-toast-success">
                <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div style="font-size: 13px; font-weight: 500; color: #0f172a; margin-bottom: 2px;">Success</div>
                    <div style="font-size: 12px; color: #64748b;">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast toast-error" id="session-toast-error">
                <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div style="font-size: 13px; font-weight: 500; color: #0f172a; margin-bottom: 2px;">Error</div>
                    <div style="font-size: 12px; color: #64748b;">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.transition = 'all 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
