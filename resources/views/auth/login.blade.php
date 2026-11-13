@extends('layouts.auth')

@section('content')
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-card border border-border rounded-3xl p-8 shadow-xl shadow-primary/5 relative overflow-hidden">

            <!-- Subtle Top Glow -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-1.5 bg-gradient-to-r from-transparent via-primary to-transparent rounded-full">
            </div>

            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white dark:bg-card p-2 shadow-md shadow-primary/10 border border-border mb-4 transition-transform hover:scale-105">
                    <img src="{{ asset('images/Logo Udinus.png') }}" alt="Logo Universitas Dian Nuswantoro"
                        class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">LabKom <span class="text-primary">Admin</span>
                </h1>
                <p class="text-sm text-muted-foreground mt-1">Sistem Pengelolaan Laporan Permasalahan Lab</p>
                <p class="text-xs font-semibold text-primary/80 mt-1 tracking-wide">Universitas Dian Nuswantoro</p>
            </div>

            @if($errors->any())
                <div
                    class="mb-6 p-3.5 rounded-2xl bg-destructive/10 border border-destructive/20 text-destructive text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-3.5 rounded-2xl bg-primary/10 border border-primary/20 text-primary text-xs font-medium">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email"
                        class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Email
                        Admin</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@labkom.id') }}" required
                            autofocus
                            class="w-full px-4 py-3 rounded-2xl bg-input border border-border text-foreground text-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    </div>
                </div>

                <div>
                    <label for="password"
                        class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" value="admin123" required
                            class="w-full px-4 py-3 rounded-2xl bg-input border border-border text-foreground text-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label
                        class="flex items-center gap-2 cursor-pointer text-xs text-muted-foreground hover:text-foreground">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-border text-primary focus:ring-primary/40" checked>
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 rounded-2xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm shadow-lg shadow-primary/25 transition-all transform active:scale-[0.98] mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Note -->
            <div class="mt-8 pt-6 border-t border-border/80 text-center">
                <p class="text-xs text-muted-foreground">Kredensial Default:</p>
                <div
                    class="inline-flex items-center gap-2 mt-1.5 px-3 py-1 rounded-full bg-muted text-[11px] font-mono text-muted-foreground">
                    <span>admin@labkom.id</span>
                    <span>•</span>
                    <span>admin123</span>
                </div>
            </div>
        </div>
    </div>
@endsection