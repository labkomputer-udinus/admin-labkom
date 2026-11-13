<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — LabKom Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Udinus.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Udinus.png') }}">

    <!-- Google Fonts: Open Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Init Dark Mode from localStorage or system preference
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</head>
<body class="h-full bg-background text-foreground font-sans antialiased selection:bg-primary/20 selection:text-primary">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 flex-shrink-0 flex flex-col border-r border-sidebar-border bg-sidebar transition-all duration-300 z-30">
            <!-- Brand / Logo -->
            <div class="h-16 px-6 flex items-center justify-between border-b border-sidebar-border/60">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-white dark:bg-sidebar-accent p-1 flex items-center justify-center shadow-md shadow-primary/15 border border-sidebar-border/80 transition-transform group-hover:scale-105 flex-shrink-0">
                        <img src="{{ asset('images/Logo Udinus.png') }}" alt="Logo Udinus" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0">
                        <h1 class="font-bold text-base tracking-tight text-sidebar-foreground truncate">LabKom <span class="text-primary">Admin</span></h1>
                        <p class="text-xs text-muted-foreground truncate">Universitas Dian Nuswantoro</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto custom-scrollbar px-3 py-4 space-y-1">
                <div class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-muted-foreground uppercase">Menu Utama</div>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('laporan.*') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Laporan Masuk</span>
                </a>

                <a href="{{ route('barang-tertinggal.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('barang-tertinggal.*') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Barang Tertinggal</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold tracking-wider text-muted-foreground uppercase">Tindak Lanjut & Logistik</div>

                <a href="{{ route('pengajuan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('pengajuan.*') || request()->routeIs('penerimaan.*') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Pengajuan Pengadaan</span>
                </a>

                <a href="{{ route('gudang.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('gudang.*') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Gudang & Stok</span>
                </a>

                <a href="{{ route('distribusi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('distribusi.*') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Distribusi ke Lab</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold tracking-wider text-muted-foreground uppercase">Dokumentasi & Rekap</div>

                <div class="space-y-1">
                    <a href="{{ route('ekspor.laporan.excel') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-medium text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh Rekap Laporan</span>
                    </a>

                    <a href="{{ route('ekspor.barang-tertinggal.excel') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-medium text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Unduh Rekap Barang Tertinggal</span>
                    </a>

                    <a href="{{ route('ekspor.distribusi.excel') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-medium text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Log Akreditasi Distribusi</span>
                    </a>
                </div>
            </div>

            <!-- Admin Profile & Logout -->
            <div class="p-3 border-t border-sidebar-border bg-sidebar/50">
                <div class="flex items-center justify-between p-2 rounded-2xl hover:bg-sidebar-accent transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center flex-shrink-0">
                            {{ substr(Auth::user()->name ?? 'Admin', 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-sidebar-foreground truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-[11px] text-muted-foreground truncate">{{ Auth::user()->email ?? 'admin@labkom.id' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-muted-foreground hover:text-destructive hover:bg-destructive/10 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Topbar Header -->
            <header class="h-16 px-6 border-b border-border bg-card/80 backdrop-blur-md flex items-center justify-between flex-shrink-0 z-20">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-bold tracking-tight text-foreground">
                        @yield('header_title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quick Create Laporan Button -->
                    <a href="{{ route('laporan.create') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-primary text-white text-xs font-semibold shadow-sm hover:bg-primary/90 transition-transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Input Laporan</span>
                    </a>

                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" type="button" class="p-2 rounded-xl border border-border text-muted-foreground hover:text-foreground hover:bg-muted transition-colors" title="Ganti Mode Gelap / Terang">
                        <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg class="w-5 h-5 block dark:hidden text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main Page Scrollable Body -->
            <main class="flex-1 overflow-y-auto custom-scrollbar p-6 sm:p-8 lg:p-10 space-y-8">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-medium animate-fade-in">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-primary/10 border border-primary/20 text-primary text-sm font-medium">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-destructive/10 border border-destructive/20 text-destructive text-sm font-medium space-y-1">
                        <div class="flex items-center gap-2 font-semibold">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Terjadi kesalahan input:</span>
                        </div>
                        <ul class="list-disc list-inside pl-2 text-xs space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
