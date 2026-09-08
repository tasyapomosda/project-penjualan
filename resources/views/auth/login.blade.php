<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Showcase Snack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #eef2ff, #f8fafc 60%, #ffffff);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
    </style>
</head>
<body class="flex items-center justify-center p-6">

    <div class="w-full max-w-sm">
        @if(session('error'))
        <div class="mb-4 animate-pulse">
            <div class="bg-red-50 border border-red-300 p-3 rounded-xl flex items-center gap-2 text-red-500 text-xs">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <div class="glass-card rounded-[2rem] p-8 shadow-xl shadow-indigo-100">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-50 mb-4">
                    <i data-lucide="lock" class="text-indigo-500 w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Admin Showcase</h1>
                <p class="text-slate-400 text-xs mt-1">Login ke Dasbor Admin</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="email" name="email" placeholder="masukkan alamat email" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-slate-800 text-sm outline-none focus:border-indigo-400 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <i data-lucide="key-round" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="password" name="password" placeholder="masukkan password" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-slate-800 text-sm outline-none focus:border-indigo-400 focus:bg-white transition-all">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 p-3.5 rounded-xl font-bold text-white text-sm shadow-lg hover:shadow-indigo-300 active:scale-95 transition-all duration-300">
                    LOGIN
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="/" class="text-slate-400 text-[10px] uppercase tracking-wider hover:text-slate-600 transition-colors">
                    ← Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>