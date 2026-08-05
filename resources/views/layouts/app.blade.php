<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ChefSync Recipes')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .font-serif-editorial {
            font-family: 'Playfair Display', Georgia, serif;
        }
        
        /* Bulletproof Food52-style Dark Navy Footer Override */
        footer.food52-footer {
            background-color: #111827 !important; /* Deep Slate/Navy Black */
            color: #f1f5f9 !important;
            padding: 64px 0 !important;
            border-top: 1px solid #1f2937 !important;
        }
        footer.food52-footer a {
            color: #94a3b8 !important;
            text-decoration: none !important;
            transition: color 0.2s ease-in-out !important;
            font-weight: 500 !important;
        }
        footer.food52-footer a:hover {
            color: #ffffff !important;
        }
        footer.food52-footer h3 {
            color: #ffffff !important;
            font-family: 'Playfair Display', Georgia, serif !important;
            font-size: 24px !important;
            font-weight: 700 !important;
            line-height: 1.3 !important;
            margin-bottom: 20px !important;
        }
        footer.food52-footer h4 {
            color: #cbd5e1 !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            margin-bottom: 16px !important;
        }
        footer.food52-footer .footer-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 48px !important;
            margin-bottom: 48px !important;
        }
        @media (min-width: 1024px) {
            footer.food52-footer .footer-grid {
                grid-template-columns: 5fr 7fr !important;
            }
        }
        footer.food52-footer .links-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 32px !important;
        }
        @media (min-width: 480px) {
            footer.food52-footer .links-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        @media (min-width: 640px) {
            footer.food52-footer .links-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }
        footer.food52-footer .newsletter-form {
            display: flex !important;
            border: 1px solid #374151 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            background-color: #1f2937 !important;
            max-width: 420px !important;
            transition: border-color 0.2s !important;
        }
        footer.food52-footer .newsletter-form:focus-within {
            border-color: #10b981 !important;
        }
        footer.food52-footer .newsletter-input {
            background: transparent !important;
            color: #ffffff !important;
            border: none !important;
            padding: 14px 16px !important;
            font-size: 14px !important;
            width: 100% !important;
            outline: none !important;
        }
        footer.food52-footer .newsletter-input::placeholder {
            color: #6b7280 !important;
        }
        footer.food52-footer .newsletter-btn {
            background-color: #ffffff !important;
            color: #111827 !important;
            border: none !important;
            padding: 14px 24px !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            letter-spacing: 0.05em !important;
            cursor: pointer !important;
            transition: background-color 0.2s !important;
            text-transform: uppercase !important;
        }
        footer.food52-footer .newsletter-btn:hover {
            background-color: #e2e8f0 !important;
        }
        footer.food52-footer .bottom-bar {
            padding-top: 32px !important;
            border-top: 1px solid #1f2937 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 16px !important;
            font-size: 12px !important;
            color: #6b7280 !important;
        }
        @media (min-width: 768px) {
            footer.food52-footer .bottom-bar {
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
        }
    </style>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Yield SEO JSON-LD Schema -->
    @yield('seo_schema')
</head>
<body class="h-full antialiased text-slate-800 flex flex-col">
    <!-- Premium Header -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Branding -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-md shadow-emerald-200 animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent tracking-tight">{{ $portalSettings->header_logo_text ?? 'ChefSync' }}</span>
                        <span class="text-xs block text-slate-400 font-semibold uppercase tracking-wider">{{ $portalSettings->header_subtitle ?? 'Culinary Portal' }}</span>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex items-center space-x-6">
                    @if(!empty($portalSettings->header_nav_links))
                        @foreach($portalSettings->header_nav_links as $link)
                            @if($link['text'] === 'Sign In')
                                <a href="{{ $link['url'] }}" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-sm transition-all hover:scale-[1.02]">{{ $link['text'] }}</a>
                            @else
                                <a href="{{ $link['url'] }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">{{ $link['text'] }}</a>
                            @endif
                        @endforeach
                    @else
                        <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Recipes</a>
                        <a href="#" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Portions Scaler</a>
                        <a href="#" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-sm transition-all hover:scale-[1.02]">Sign In</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    <!-- Dark Editorial Newsletter Footer (Food52 style) -->
    <footer class="food52-footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Grid -->
            <div class="footer-grid">
                <!-- Left Column (Newsletter) -->
                <div class="space-y-6">
                    <h3>
                        {{ $portalSettings->footer_newsletter_title ?? 'Our best tips for eating thoughtfully and living joyfully, right in your inbox.' }}
                    </h3>
                    
                    <form action="#" method="POST" class="max-w-md">
                        <div class="newsletter-form">
                            <input 
                                type="email" 
                                placeholder="{{ $portalSettings->footer_newsletter_placeholder ?? 'ex: myname@email.com' }}"
                                class="newsletter-input"
                                required
                            />
                            <button type="submit" class="newsletter-btn">
                                {{ $portalSettings->footer_newsletter_button ?? 'SUBSCRIBE' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Columns (Links) -->
                <div class="links-grid">
                    @if(!empty($portalSettings->footer_columns_json))
                        @foreach($portalSettings->footer_columns_json as $col)
                            <div class="space-y-4">
                                <h4>{{ $col['title'] }}</h4>
                                <ul class="space-y-2.5" style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($col['links'] as $link)
                                        <li>
                                            <a href="{{ $link['url'] }}">
                                                {{ $link['text'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback column -->
                        <div class="space-y-4">
                            <h4>Company</h4>
                            <ul class="space-y-2.5" style="list-style: none; padding: 0; margin: 0;">
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">DEI Vision</a></li>
                                <li><a href="#">Press</a></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Copyright & Policies -->
            <div class="bottom-bar">
                <div>
                    {{ $portalSettings->footer_copyright ?? '© ' . date('Y') . ' ChefSync, Inc. All Rights Reserved' }}
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 24px;">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                    <a href="#">Code of Conduct</a>
                    <a href="#">Accessibility Policy</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
