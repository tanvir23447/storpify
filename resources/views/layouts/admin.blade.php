<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'STORPIFY Admin Control Center' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

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
<body class="antialiased min-h-screen bg-[#F8F9FD]" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#17152B] text-white flex flex-col transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Sidebar Header / Logo -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-purple-900/30">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="STORPIFY" class="h-9 w-auto brightness-110">
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Role Badge -->
            <div class="px-6 py-3 bg-[#121024] border-b border-purple-900/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ auth()->user()->isSuperAdmin() ? 'bg-purple-400 animate-pulse' : (auth()->user()->isGeneralAdmin() ? 'bg-blue-400' : 'bg-emerald-400') }}"></span>
                    <span class="text-xs font-bold text-gray-200">
                        {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}
                    </span>
                </div>
                <span class="text-[10px] bg-purple-950 text-purple-300 font-mono px-2 py-0.5 rounded-full border border-purple-800">
                    RBAC v2
                </span>
            </div>

            <!-- Sidebar Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 text-xs font-medium">

                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white font-bold shadow-md' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'ড্যাশবোর্ড' : 'Dashboard' }}</span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                    {{ app()->getLocale() === 'bn' ? 'মেইন স্টোর' : 'MAIN STORE' }}
                </div>

                <!-- 2. Products -->
                @if(auth()->user()->hasPermission('product.view'))
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="box" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'প্রোডাক্ট ম্যানেজমেন্ট' : 'Products' }}</span>
                    </a>
                @endif

                <!-- 3. Categories -->
                @if(auth()->user()->hasPermission('category.view'))
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="folder-tree" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'ক্যাটাগরি ম্যানেজমেন্ট' : 'Categories' }}</span>
                    </a>
                @endif

                <!-- 4. Orders -->
                @if(auth()->user()->hasPermission('order.view'))
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="flex items-center gap-3">
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-purple-400"></i>
                            <span>{{ app()->getLocale() === 'bn' ? 'অর্ডার সমূহ' : 'Orders' }}</span>
                        </span>
                        @php $pendingOrders = \App\Models\Order::where('payment_status', 'pending')->count(); @endphp
                        @if($pendingOrders > 0)
                            <span class="px-2 py-0.5 text-[10px] bg-yellow-500 text-gray-950 font-bold rounded-full">
                                {{ $pendingOrders }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- 5. Payments Verification -->
                @if(auth()->user()->hasPermission('payment.view'))
                    <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="credit-card" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'পেমেন্ট ভেরিফিকেশন' : 'Payment Verification' }}</span>
                    </a>
                @endif

                <!-- 6. Customers -->
                @if(auth()->user()->hasPermission('customer.view'))
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="users" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'কাস্টমার লিস্ট' : 'Customers' }}</span>
                    </a>
                @endif

                <!-- 7. Bangladeshi Payment Methods & Numbers Settings -->
                @if(auth()->user()->hasPermission('payment.settings.manage'))
                    <a href="{{ route('admin.payment-methods.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.payment-methods.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="wallet" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'পেমেন্ট নম্বর সেটিংস' : 'Payment Numbers & Methods' }}</span>
                    </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                    {{ app()->getLocale() === 'bn' ? 'হোমপেজ ও সিএমএস' : 'HOMEPAGE & CMS' }}
                </div>

                <!-- 8. Homepage Builder -->
                @if(auth()->user()->hasPermission('homepage.view'))
                    <a href="{{ route('admin.cms.sections') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.cms.sections*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="layers" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'হোমপেজ বিল্ডার' : 'Homepage Builder' }}</span>
                    </a>
                @endif

                <!-- 9. Hero Slider -->
                @if(auth()->user()->hasPermission('hero.view'))
                    <a href="{{ route('admin.cms.hero') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.cms.hero*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="image" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'হিরো স্লাইডার' : 'Hero Slider' }}</span>
                    </a>
                @endif

                <!-- 10. Offer Popups -->
                @if(auth()->user()->hasPermission('popup.view'))
                    <a href="{{ route('admin.cms.popups') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.cms.popups*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="message-square" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'অফার পপআপ ম্যানেজার' : 'Offer Popups' }}</span>
                    </a>
                @endif

                <!-- 11. Coupons -->
                @if(auth()->user()->hasPermission('order.view'))
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.coupons.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="ticket" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'কুপন ম্যানেজার' : 'Coupons' }}</span>
                    </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                    {{ app()->getLocale() === 'bn' ? 'আলাদা মডিউল' : 'DEDICATED MODULE' }}
                </div>

                <!-- 12. WEB SUBSCRIPTION MANAGEMENT (SEPARATE MODULE) -->
                @if(auth()->user()->hasPermission('subscription.view'))
                    <a href="{{ route('admin.subscriptions.dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-purple-900/60 to-indigo-900/60 border border-purple-500/40 text-purple-200 font-bold hover:text-white transition-all">
                        <span class="flex items-center gap-2.5">
                            <span>👑</span>
                            <span>{{ app()->getLocale() === 'bn' ? 'ওয়েব সাবস্ক্রিপশন' : 'Web Subscription' }}</span>
                        </span>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-purple-400"></i>
                    </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                    {{ app()->getLocale() === 'bn' ? 'সিস্টেম ও সিকিউরিটি' : 'SYSTEM & RBAC' }}
                </div>

                <!-- 13. Staff & Permissions (Super Admin Only) -->
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.staff.*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="shield-alert" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'স্টাফ ও পারমিশন' : 'Staff & Access (RBAC)' }}</span>
                    </a>
                @endif

                <!-- 14. Audit Logs (Super Admin Only) -->
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.audit-logs') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.audit-logs*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="history" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'অডিট ও সিকিউরিটি লগ' : 'Audit & Security Logs' }}</span>
                    </a>
                @endif

                <!-- 15. System Preflight & Hostinger Settings -->
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.settings.preflight') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.settings.preflight*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="server" class="w-4 h-4 text-purple-400"></i>
                        <span>{{ app()->getLocale() === 'bn' ? 'হোস্টিং ও সুপাবেস চেক' : 'Hostinger & Supabase Gate' }}</span>
                    </a>
                @endif

            </div>

            <!-- Footer / User info -->
            <div class="p-4 border-t border-purple-900/30 bg-[#121024] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400 truncate max-w-[120px]">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 text-gray-400 hover:text-red-400 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Admin Header -->
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-600 hover:text-purple-600">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="text-base sm:text-lg font-bold text-gray-900">
                        {{ $headerTitle ?? 'STORPIFY Control Center' }}
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <i data-lucide="external-link" class="w-3.5 h-3.5 text-purple-600"></i>
                        <span class="hidden sm:inline">View Storefront</span>
                    </a>
                </div>
            </header>

            <!-- Admin Body Scrollable Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl flex items-center justify-between text-green-800 text-sm">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center justify-between text-red-800 text-sm">
                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

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
