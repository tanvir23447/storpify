<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'STORPIFY Web Subscription Control' }}</title>

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
        
        <!-- SUBSCRIPTION MODULE SIDEBAR -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#17152B] text-white flex flex-col transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="h-20 flex items-center justify-between px-6 border-b border-purple-900/30">
                <a href="{{ route('admin.subscriptions.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="STORPIFY" class="h-9 w-auto brightness-110">
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Module Tag -->
            <div class="px-6 py-3 bg-[#121024] border-b border-purple-900/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span>👑</span>
                    <span class="text-xs font-bold text-yellow-400">
                        {{ app()->getLocale() === 'bn' ? 'ওয়েব সাবস্ক্রিপশন মডিউল' : 'WEB SUBSCRIPTION' }}
                    </span>
                </div>
                <a href="{{ route('admin.dashboard') }}" title="Back to Main Store Admin" class="text-[10px] text-purple-300 hover:text-white underline">
                    Main Store ↗
                </a>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 text-xs font-medium">

                <!-- 1. Dashboard -->
                <a href="{{ route('admin.subscriptions.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.dashboard') ? 'bg-purple-600 text-white font-bold shadow-md' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'ড্যাশবোর্ড' : 'Dashboard' }}</span>
                </a>

                <!-- 2. Plans -->
                <a href="{{ route('admin.subscriptions.plans') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.plans*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="award" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'সাবস্ক্রিপশন প্ল্যান' : 'Subscription Plans' }}</span>
                </a>

                <!-- 3. Categories -->
                <a href="{{ route('admin.subscriptions.categories') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.categories*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="folder-tree" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'ক্যাটাগরি ম্যানেজমেন্ট' : 'Categories' }}</span>
                </a>

                <!-- 4. Files Library -->
                <a href="{{ route('admin.subscriptions.files') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.files*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="file-archive" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'ফাইল ম্যানেজমেন্ট' : 'File Library' }}</span>
                </a>

                <!-- 5. Subscribers -->
                <a href="{{ route('admin.subscriptions.subscribers') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.subscribers*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="users" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'সাবস্ক্রাইবার তালিকা' : 'Subscribers' }}</span>
                </a>

                <!-- 6. Subscription Orders & Payments -->
                <a href="{{ route('admin.subscriptions.orders') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.orders*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="credit-card" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'ট্রানজেকশন ও অর্ডার' : 'Transactions & Orders' }}</span>
                </a>

                <!-- 7. Download Logs -->
                <a href="{{ route('admin.subscriptions.downloads') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.downloads*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="download-cloud" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'ডাউনলোড লগ' : 'Download Logs' }}</span>
                </a>

                <!-- 8. Settings & Base Slug -->
                <a href="{{ route('admin.subscriptions.settings') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscriptions.settings*') ? 'bg-purple-600 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="settings" class="w-4 h-4 text-purple-400"></i>
                    <span>{{ app()->getLocale() === 'bn' ? 'সাবস্ক্রিপশন সেটিংস' : 'Settings & Base Slug' }}</span>
                </a>

            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-purple-900/30 bg-[#121024] flex items-center justify-between">
                <a href="{{ route('subscription.index') }}" target="_blank" class="text-xs text-purple-300 hover:text-white flex items-center gap-1.5 font-semibold">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Public Library ↗</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="p-1 text-gray-400 hover:text-red-400">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-600 hover:text-purple-600">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <div>
                        <span class="text-xs font-bold text-purple-600 uppercase">Independent Module</span>
                        <h1 class="text-base sm:text-lg font-bold text-gray-900">
                            {{ $headerTitle ?? 'Web Subscription Management' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-purple-50 text-xs font-bold text-gray-700 hover:text-purple-700 transition-colors">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Main Store</span>
                    </a>
                </div>
            </header>

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
