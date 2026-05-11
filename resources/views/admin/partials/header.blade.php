<header class="h-16 flex justify-between items-center px-6 lg:px-8 shrink-0 border-b border-slate-200 bg-white/90 backdrop-blur-md z-10">
    <h1 class="text-lg font-bold text-slate-800">{{ $title ?? 'Admin Panel' }}</h1>
    <div class="flex items-center space-x-4">
        <span class="text-sm text-slate-500 hidden sm:block">
            <i class="fa-regular fa-calendar mr-1"></i>
            {{ now()->translatedFormat('d F Y') }}
        </span>
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <span class="text-sm font-medium text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
        </div>
    </div>
</header>
