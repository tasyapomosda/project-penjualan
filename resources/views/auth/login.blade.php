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
            background: radial-gradient(circle at bottom right, #1e1b4b, #000000);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center p-6">

    <div class="w-full max-w-sm">
        @if(session('error'))
        <div class="mb-4 animate-pulse">
            <div class="bg-red-500/10 border border-red-500/50 p-3 rounded-xl flex items-center gap-2 text-red-400 text-xs">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <div class="glass-card rounded-[2rem] p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-purple-500/10 mb-4">
                    <i data-lucide="lock" class="text-purple-500 w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Admin Access</h1>
                <p class="text-zinc-500 text-xs mt-1">Silakan masuk ke pusat kendali</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-[10px] font-semibold text-zinc-400 uppercase tracking-widest ml-1">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500"></i>
                        <input type="email" name="email" placeholder="admin@gmail.com" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white text-sm outline-none focus:border-purple-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-semibold text-zinc-400 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <i data-lucide="key-round" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500"></i>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white text-sm outline-none focus:border-purple-500 transition-all">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 p-3.5 rounded-xl font-bold text-white text-sm shadow-lg hover:shadow-purple-500/20 active:scale-95 transition-all duration-300">
                    LOGIN
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="/" class="text-zinc-600 text-[10px] uppercase tracking-wider hover:text-zinc-400 transition-colors">
                    ← Kembali ke Toko
                </a>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>