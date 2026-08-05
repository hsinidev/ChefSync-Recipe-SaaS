<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — ChefSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; font-family: 'Inter', sans-serif; }

        :root {
            --blue-50:  #eff6ff;
            --blue-100: #dbeafe;
            --blue-200: #bfdbfe;
            --blue-400: #60a5fa;
            --blue-500: #3b82f6;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
            --blue-800: #1e40af;
            --blue-900: #1e3a8a;
            --slate-50:  #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
        }

        body {
            background: var(--slate-50);
            color: var(--slate-800);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .cs-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 256px;
            background: linear-gradient(180deg, var(--blue-900) 0%, var(--blue-800) 60%, #1a3680 100%);
            display: flex;
            flex-direction: column;
            z-index: 50;
            box-shadow: 4px 0 24px rgba(30,58,138,0.18);
        }

        .cs-logo {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .cs-logo-icon {
            width: 34px; height: 34px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .cs-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }

        .cs-nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 12px 12px 6px;
            display: block;
        }

        .cs-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            transition: all 0.15s ease;
            margin-bottom: 2px;
            border: 1px solid transparent;
        }
        .cs-nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .cs-nav-link.active {
            background: rgba(255,255,255,0.14);
            color: #fff;
            border-color: rgba(255,255,255,0.12);
        }
        .cs-nav-link.active .cs-nav-dot {
            background: #60a5fa;
            box-shadow: 0 0 6px #60a5fa;
        }
        .cs-nav-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: transparent;
            flex-shrink: 0;
        }
        .cs-nav-icon {
            width: 18px; height: 18px;
            flex-shrink: 0;
            opacity: 0.75;
        }
        .cs-nav-link.active .cs-nav-icon { opacity: 1; }

        /* ── Topbar ── */
        .cs-topbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        /* ── Cards ── */
        .cs-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--slate-200);
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        /* ── Stat Cards ── */
        .cs-stat {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--slate-200);
            padding: 20px 22px;
            transition: box-shadow 0.15s;
        }
        .cs-stat:hover { box-shadow: 0 4px 20px rgba(37,99,235,0.1); border-color: var(--blue-200); }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11.5px; font-weight: 600; }
        .badge-published { background: #dcfce7; color: #16a34a; }
        .badge-draft     { background: var(--slate-100); color: var(--slate-500); }
        .badge-review    { background: #fef9c3; color: #b45309; }
        .badge-free       { background: var(--blue-50); color: var(--blue-700); }
        .badge-pro        { background: #f3e8ff; color: #7c3aed; }
        .badge-enterprise { background: #fff7ed; color: #c2410c; }

        /* ── Form Inputs ── */
        .cs-input {
            width: 100%;
            background: var(--slate-50);
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .cs-input:focus {
            border-color: var(--blue-400);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
            background: #fff;
        }
        .cs-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--slate-500);
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        /* ── Buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--blue-600);
            color: #fff;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.15s;
            border: none; cursor: pointer;
        }
        .btn-primary:hover { background: var(--blue-700); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
        .btn-primary:active { transform: scale(0.98); }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--slate-100);
            color: var(--slate-700);
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.15s;
            border: none; cursor: pointer;
        }
        .btn-secondary:hover { background: var(--slate-200); }

        .btn-danger {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.15s;
            border: none; cursor: pointer;
        }
        .btn-danger:hover { background: #fecaca; }

        /* ── Table ── */
        .cs-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .cs-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--slate-400);
            border-bottom: 1px solid var(--slate-100);
        }
        .cs-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--slate-100);
            color: var(--slate-700);
            vertical-align: middle;
        }
        .cs-table tr:last-child td { border-bottom: none; }
        .cs-table tbody tr:hover { background: var(--blue-50); }

        /* ── Icon btn ── */
        .icon-btn {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.15s;
            cursor: pointer;
        }
        .icon-btn-blue  { background: var(--blue-50); color: var(--blue-600); }
        .icon-btn-blue:hover  { background: var(--blue-100); }
        .icon-btn-red   { background: #fee2e2; color: #dc2626; }
        .icon-btn-red:hover   { background: #fecaca; }
        .icon-btn-slate { background: var(--slate-100); color: var(--slate-600); }
        .icon-btn-slate:hover { background: var(--slate-200); }

        /* ── Flash ── */
        .cs-flash-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
        }
        .cs-flash-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 99px; }
    </style>
</head>
<body>

<div style="display:flex; min-height:100vh;">

    {{-- ═══════════════════════════════ SIDEBAR ══════════════════════════════ --}}
    <aside class="cs-sidebar">

        {{-- Logo --}}
        <div class="cs-logo">
            <div class="cs-logo-icon">🍴</div>
            <div style="margin-left:12px;">
                <div style="font-size:14px; font-weight:700; color:#fff; line-height:1.2;">ChefSync</div>
                <div style="font-size:11px; color:rgba(255,255,255,0.45);">Admin Console</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="cs-nav">
            <span class="cs-nav-label">Overview</span>

            <a href="{{ route('admin.dashboard') }}"
               class="cs-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/>
                </svg>
                Dashboard
            </a>

            <span class="cs-nav-label" style="margin-top:8px;">Content</span>

            <a href="{{ route('admin.recipes.index') }}"
               class="cs-nav-link {{ request()->routeIs('admin.recipes.*') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Recipes
                @php $draftCount = \App\Models\Recipe::withoutGlobalScopes()->where('status','draft')->count(); @endphp
                @if($draftCount > 0)
                <span style="margin-left:auto; background:rgba(96,165,250,0.2); color:#93c5fd; font-size:10px; font-weight:700; padding:1px 7px; border-radius:99px;">{{ $draftCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="cs-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Categories
            </a>

            <a href="{{ route('admin.slides.index') }}"
               class="cs-nav-link {{ request()->routeIs('admin.slides.*') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Hero Slides
            </a>

            <span class="cs-nav-label" style="margin-top:8px;">Platform</span>

            <a href="{{ route('admin.tenants.index') }}"
               class="cs-nav-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Tenants
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="cs-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Users
            </a>

            <a href="{{ route('admin.settings.edit') }}"
               class="cs-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>

            <span class="cs-nav-label" style="margin-top:8px;">Site</span>

            <a href="{{ url('/') }}" target="_blank"
               class="cs-nav-link">
                <div class="cs-nav-dot"></div>
                <svg class="cs-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Live Site
                <svg style="width:10px;height:10px;margin-left:2px;opacity:.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </nav>

        {{-- User footer --}}
        <div style="padding:16px 12px; border-top:1px solid rgba(255,255,255,0.08);">
            <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:10px;background:rgba(255,255,255,0.06);">
                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#818cf8);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">A</div>
                <div>
                    <div style="font-size:12.5px;font-weight:600;color:#fff;">Administrator</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);">super_admin</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═════════════════════════════ MAIN AREA ═════════════════════════════ --}}
    <div style="flex:1; margin-left:256px; display:flex; flex-direction:column; min-height:100vh;">

        {{-- Topbar --}}
        <header class="cs-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                {{-- Breadcrumb --}}
                <span style="font-size:11.5px;color:var(--slate-400);">ChefSync</span>
                <span style="color:var(--slate-300);">/</span>
                <span style="font-size:13.5px;font-weight:600;color:var(--slate-800);">@yield('page-title', 'Dashboard')</span>
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                @if(session('success'))
                <div style="display:flex;align-items:center;gap:6px;background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:6px 12px;border-radius:8px;font-size:12.5px;font-weight:500;">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                <a href="{{ route('admin.recipes.create') }}" class="btn-primary" style="padding:8px 14px;font-size:12.5px;">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Recipe
                </a>

                <a href="{{ url('/') }}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12.5px;font-weight:600;background:var(--blue-50);color:var(--blue-700);text-decoration:none;transition:all .15s;"
                   onmouseover="this.style.background='var(--blue-100)'" onmouseout="this.style.background='var(--blue-50)'">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview Site
                </a>
            </div>
        </header>

        {{-- Content --}}
        <main style="flex:1;padding:32px;">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer style="padding:16px 32px;border-top:1px solid var(--slate-200);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:12px;color:var(--slate-400);">ChefSync Admin v1.0 · Multi-Tenant Recipe Platform</span>
            <span style="font-size:12px;color:var(--slate-400);">{{ now()->format('D, d M Y · H:i') }}</span>
        </footer>
    </div>
</div>

</body>
</html>
