<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('storpify.brand.name') . ' — ' . (app()->getLocale() === 'bn' ? config('storpify.brand.tagline_bn') : config('storpify.brand.tagline')) }}</title>

    <!-- Google Fonts: Inter & Hind Siliguri for Bangla -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
            background-color: #F8F9FD;
            color: #17152B;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col selection:bg-purple-500 selection:text-white" x-data="{ mobileMenuOpen: false, searchOpen: false }">

    <!-- 1. Top Announcement Bar (Dynamic from CMS) -->
    @php
        $announcementSection = \App\Models\HomepageSection::where('section_type', 'announcement_bar')->where('is_active', true)->first();
    @endphp
    @if($announcementSection)
        <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-700 text-white text-xs md:text-sm font-medium py-2 px-4 shadow-sm text-center relative z-50">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <span>{{ $announcementSection->content['text'] ?? '🔥 বড় অফার চলছে! নির্বাচিত ডিজিটাল প্রোডাক্টে সর্বোচ্চ ৭০% ছাড়!' }}</span>
                <a href="{{ $announcementSection->content['link_url'] ?? '/shop' }}" class="underline font-bold hover:text-yellow-200 transition-colors ml-1">
                    {{ $announcementSection->content['link_text'] ?? (app()->getLocale() === 'bn' ? 'এখনই দেখুন' : 'Shop Now') }}
                </a>
            </div>
        </div>
    @endif

    <!-- 2. Main Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="{{ config('storpify.brand.name') }}" class="h-10 md:h-12 w-auto object-contain">
                    </a>
                </div>

                <!-- Desktop Search Bar -->
                <div class="hidden md:flex flex-1 max-w-xl mx-4">
                    <form action="{{ route('shop') }}" method="GET" class="w-full relative">
                        <div class="relative flex items-center">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}" 
                                class="w-full pl-5 pr-12 py-2.5 rounded-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-600 focus:ring-2 focus:ring-purple-100 text-sm transition-all outline-none">
                            <button type="submit" class="absolute right-1.5 p-2 bg-purple-600 hover:bg-purple-700 text-white rounded-full transition-colors flex items-center justify-center">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3 sm:gap-4">
                    
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                            <i data-lucide="globe" class="w-3.5 h-3.5 text-purple-600"></i>
                            <span>{{ app()->getLocale() === 'bn' ? 'বাংলা' : 'EN' }}</span>
                            <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400"></i>
                        </button>
                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 text-xs">
                            <a href="?lang=bn" class="flex items-center gap-2 px-3 py-2 hover:bg-purple-50 text-gray-700 {{ app()->getLocale() === 'bn' ? 'font-bold text-purple-600' : '' }}">
                                <span>🇧🇩</span> বাংলা (Bengali)
                            </a>
                            <a href="?lang=en" class="flex items-center gap-2 px-3 py-2 hover:bg-purple-50 text-gray-700 {{ app()->getLocale() === 'en' ? 'font-bold text-purple-600' : '' }}">
                                <span>🇺🇸</span> English
                            </a>
                        </div>
                    </div>

                    <!-- Wishlist Icon -->
                    <a href="{{ route('customer.wishlist') }}" class="p-2 text-gray-600 hover:text-purple-600 relative transition-colors hidden sm:flex items-center">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                    </a>

                    <!-- Cart Icon with Livewire Badge -->
                    <a href="{{ route('cart') }}" class="p-2 text-gray-600 hover:text-purple-600 relative transition-colors flex items-center">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        @php $cCount = cart_count(); @endphp
                        @if($cCount > 0)
                            <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                {{ app()->getLocale() === 'bn' ? bangla_number($cCount) : $cCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Account / Auth -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden lg:inline text-xs font-semibold text-gray-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 hidden lg:inline"></i>
                            </button>
                            <div x-show="open" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-sm">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded-full {{ auth()->user()->isStaff() ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                                    </span>
                                </div>

                                @if(auth()->user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-purple-700 hover:bg-purple-50 font-semibold text-xs">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        <span>{{ __('messages.nav.admin_panel') }}</span>
                                    </a>
                                @endif

                                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-gray-700 hover:bg-gray-50 text-xs font-medium">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ __('messages.nav.dashboard') }}</span>
                                </a>

                                <a href="{{ route('customer.downloads') }}" class="flex items-center gap-2 px-4 py-2.5 text-gray-700 hover:bg-gray-50 text-xs font-medium">
                                    <i data-lucide="download" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ app()->getLocale() === 'bn' ? 'আমার ডাউনলোডস' : 'My Downloads' }}</span>
                                </a>

                                <a href="{{ route('customer.subscription') }}" class="flex items-center gap-2 px-4 py-2.5 text-gray-700 hover:bg-gray-50 text-xs font-medium">
                                    <i data-lucide="crown" class="w-4 h-4 text-yellow-500"></i>
                                    <span>{{ app()->getLocale() === 'bn' ? 'আমার সাবস্ক্রিপশন' : 'My Subscription' }}</span>
                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 text-xs font-semibold">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        <span>{{ __('messages.nav.logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-full shadow-xs transition-colors">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            <span>{{ __('messages.nav.login') }}</span>
                        </a>
                    @endauth

                    <!-- Mobile Menu Trigger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 hover:text-purple-600">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. Desktop Sub-Navigation Bar -->
        <div class="hidden md:block bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-12">
                
                <!-- Category Dropdown Pill -->
                <div class="relative" x-data="{ catOpen: false }">
                    <button @click="catOpen = !catOpen" @click.away="catOpen = false" 
                        class="flex items-center gap-2 px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg text-xs font-bold transition-colors">
                        <i data-lucide="grid" class="w-3.5 h-3.5"></i>
                        <span>{{ __('messages.all_categories') }}</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </button>

                    <div x-show="catOpen" x-cloak class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                        @php
                            $navCategories = \App\Models\ProductCategory::active()->orderBy('sort_order')->get();
                        @endphp
                        @foreach($navCategories as $navCat)
                            <a href="{{ route('category.show', $navCat->slug) }}" class="flex items-center justify-between px-4 py-2.5 hover:bg-purple-50 text-gray-700 hover:text-purple-700 text-xs font-medium transition-colors">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="{{ $navCat->icon ?? 'folder' }}" class="w-4 h-4 text-purple-600"></i>
                                    {{ app()->getLocale() === 'bn' && $navCat->name_bn ? $navCat->name_bn : $navCat->name }}
                                </span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex items-center gap-6 text-sm font-semibold text-gray-700">
                    <a href="{{ route('home') }}" class="hover:text-purple-600 transition-colors {{ request()->routeIs('home') ? 'text-purple-600' : '' }}">
                        {{ __('messages.nav.home') }}
                    </a>
                    <a href="{{ route('shop') }}" class="hover:text-purple-600 transition-colors {{ request()->routeIs('shop') ? 'text-purple-600' : '' }}">
                        {{ __('messages.nav.digital_products') }}
                    </a>
                    <a href="{{ route('subscription.index') }}" class="flex items-center gap-1.5 text-purple-700 hover:text-purple-900 font-bold transition-colors">
                        <span>👑</span>
                        <span>{{ __('messages.nav.web_subscription') }}</span>
                    </a>
                    <a href="{{ route('shop', ['filter' => 'discounted']) }}" class="hover:text-purple-600 transition-colors">
                        {{ __('messages.nav.offers') }}
                    </a>
                    <a href="{{ route('faq') }}" class="hover:text-purple-600 transition-colors">
                        {{ __('messages.nav.help') }}
                    </a>
                </nav>

                <!-- Phone / Direct Contact -->
                <div class="hidden lg:flex items-center gap-2 text-xs font-semibold text-gray-500">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5 text-purple-600"></i>
                    <span>+880 1700-000000</span>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-100 bg-white px-4 pt-3 pb-6 space-y-3">
            <form action="{{ route('shop') }}" method="GET" class="w-full relative">
                <input type="text" name="q" placeholder="{{ __('messages.search_placeholder') }}" class="w-full pl-4 pr-10 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs">
                <button type="submit" class="absolute right-2 top-2 text-purple-600">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="flex flex-col space-y-2 text-sm font-semibold text-gray-700 pt-2">
                <a href="{{ route('home') }}" class="py-2 border-b border-gray-50">{{ __('messages.nav.home') }}</a>
                <a href="{{ route('shop') }}" class="py-2 border-b border-gray-50">{{ __('messages.nav.digital_products') }}</a>
                <a href="{{ route('subscription.index') }}" class="py-2 border-b border-gray-50 text-purple-700 font-bold">👑 {{ __('messages.nav.web_subscription') }}</a>
                <a href="{{ route('shop', ['filter' => 'discounted']) }}" class="py-2 border-b border-gray-50">{{ __('messages.nav.offers') }}</a>
                <a href="{{ route('faq') }}" class="py-2">{{ __('messages.nav.help') }}</a>
            </div>
        </div>
    </header>

    <!-- Flash Alert Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl flex items-center justify-between text-green-800 text-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center justify-between text-red-800 text-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1 pb-16 md:pb-0">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- 4. Footer -->
    <footer class="bg-[#121024] text-white pt-16 pb-20 md:pb-8 border-t border-purple-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-gray-800">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="{{ config('storpify.brand.name') }}" class="h-10 w-auto brightness-110">
                    <p class="text-xs text-gray-400 leading-relaxed">
                        {{ __('messages.footer.about') }}
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://facebook.com" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-purple-600 flex items-center justify-center text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="https://youtube.com" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-purple-600 flex items-center justify-center text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="youtube" class="w-4 h-4"></i>
                        </a>
                        <a href="https://t.me" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-purple-600 flex items-center justify-center text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </a>
                        <a href="https://wa.me" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-purple-600 flex items-center justify-center text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-sm font-bold text-white mb-4">{{ __('messages.footer.quick_links') }}</h4>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.nav.home') }}</a></li>
                        <li><a href="{{ route('shop') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.nav.digital_products') }}</a></li>
                        <li><a href="{{ route('subscription.index') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.nav.web_subscription') }}</a></li>
                        <li><a href="{{ route('shop', ['filter' => 'discounted']) }}" class="hover:text-purple-400 transition-colors">{{ __('messages.nav.offers') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.footer.faq') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Help & Support -->
                <div>
                    <h4 class="text-sm font-bold text-white mb-4">{{ __('messages.footer.help_support') }}</h4>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li><a href="{{ route('customer.tickets') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.footer.support_ticket') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.footer.refund_policy') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.footer.terms') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-purple-400 transition-colors">{{ __('messages.footer.contact') }}</a></li>
                    </ul>
                </div>

                <!-- Col 4: Payment Badges -->
                <div>
                    <h4 class="text-sm font-bold text-white mb-4">{{ __('messages.footer.payment_methods') }}</h4>
                    <p class="text-xs text-gray-400 mb-3">আমরা গ্রহণ করি বিকাশ, নগদ, রকেট ও সকল প্রধান কার্ড:</p>
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="px-3 py-1.5 bg-pink-600 text-white font-bold text-xs rounded-lg shadow-xs">bKash</span>
                        <span class="px-3 py-1.5 bg-orange-600 text-white font-bold text-xs rounded-lg shadow-xs">Nagad</span>
                        <span class="px-3 py-1.5 bg-purple-600 text-white font-bold text-xs rounded-lg shadow-xs">Rocket</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-green-400 font-semibold">
                        <i data-lucide="shield-check" class="w-4 h-4 text-green-400"></i>
                        <span>SSL Secure 256-bit Encryption</span>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-3">
                <p>© 2026 STORPIFY. {{ __('messages.footer.rights') }} | {{ __('messages.footer.made_with') }}</p>
                <div class="flex items-center gap-4">
                    <span>Privacy Policy</span>
                    <span>•</span>
                    <span>Terms & Conditions</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- 5. Mobile Bottom App Bar (Mobile Viewport) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 flex items-center justify-around py-2 px-1 shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('home') ? 'text-purple-600 font-bold' : 'text-gray-500' }}">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span>{{ __('messages.nav.home') }}</span>
        </a>
        <a href="{{ route('shop') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('shop') ? 'text-purple-600 font-bold' : 'text-gray-500' }}">
            <i data-lucide="grid" class="w-5 h-5"></i>
            <span>ক্যাটাগরি</span>
        </a>
        <a href="{{ route('subscription.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('subscription.*') ? 'text-purple-600 font-bold' : 'text-gray-500' }}">
            <i data-lucide="crown" class="w-5 h-5 text-yellow-500"></i>
            <span>সাবস্ক্রিপশন</span>
        </a>
        <a href="{{ route('cart') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold relative {{ request()->routeIs('cart') ? 'text-purple-600 font-bold' : 'text-gray-500' }}">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            @if($cCount > 0)
                <span class="absolute -top-1 right-2 bg-purple-600 text-white text-[9px] font-bold w-3.5 h-3.5 rounded-full flex items-center justify-center">
                    {{ $cCount }}
                </span>
            @endif
            <span>কার্ট</span>
        </a>
        <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('customer.*') ? 'text-purple-600 font-bold' : 'text-gray-500' }}">
            <i data-lucide="user" class="w-5 h-5"></i>
            <span>অ্যাকাউন্ট</span>
        </a>
    </div>

    <!-- 6. Offer Popup System (Section 16) -->
    @php
        $popup = \App\Models\PopupCampaign::active()->first();
    @endphp
    @if($popup)
        <div x-data="{
                showPopup: false,
                init() {
                    if (!sessionStorage.getItem('popup_dismissed_{{ $popup->id }}')) {
                        setTimeout(() => { this.showPopup = true; }, {{ $popup->display_delay_sec * 1000 }});
                    }
                },
                dismiss() {
                    this.showPopup = false;
                    sessionStorage.setItem('popup_dismissed_{{ $popup->id }}', 'true');
                }
            }"
            x-show="showPopup"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity">
            <div @click.away="dismiss()" class="bg-[#17152B] border border-purple-500/30 text-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative overflow-hidden text-center">
                <button @click="dismiss()" class="absolute top-4 right-4 text-gray-400 hover:text-white p-1 rounded-full bg-white/10">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <div class="inline-block p-3 bg-purple-600/30 rounded-2xl mb-3 text-purple-300">
                    <i data-lucide="sparkles" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ app()->getLocale() === 'bn' && $popup->title_bn ? $popup->title_bn : $popup->title }}</h3>
                <p class="text-xs text-gray-300 mb-4">{{ app()->getLocale() === 'bn' && $popup->description_bn ? $popup->description_bn : $popup->description }}</p>
                @if($popup->coupon_code)
                    <div class="bg-white/10 border border-purple-400/30 rounded-xl py-2 px-4 mb-4 flex items-center justify-between">
                        <span class="text-xs text-purple-200">Coupon Code:</span>
                        <span class="font-mono font-bold text-yellow-300 text-sm tracking-wider">{{ $popup->coupon_code }}</span>
                    </div>
                @endif
                <a href="{{ $popup->button_url ?? '/shop' }}" @click="dismiss()" class="block w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg transition-transform hover:scale-[1.02]">
                    {{ $popup->button_text ?? (app()->getLocale() === 'bn' ? 'অফারটি উপভোগ করুন' : 'Claim Offer') }}
                </a>
            </div>
        </div>
    @endif

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
        document.addEventListener('livewire:navigated', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
