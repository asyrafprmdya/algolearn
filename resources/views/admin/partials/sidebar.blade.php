<aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between hidden md:flex shrink-0">
    <div class="flex-1 overflow-y-auto">
        <div class="h-20 flex items-center px-6 border-b border-slate-800 mb-4 sticky top-0 bg-slate-900 z-10">
            <div class="flex items-center space-x-3 text-white">
                <div class="bg-indigo-600 text-white p-2 rounded-lg shadow-lg shadow-indigo-600/20">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="font-bold text-lg leading-tight">AlgoAdmin</h2>
                    <p class="text-xs text-slate-400">Control Panel</p>
                </div>
            </div>
        </div>

        <nav class="px-4 space-y-1 mb-4">
            @php
                $current = request()->routeIs('admin.dashboard') ? 'dashboard'
                    : (request()->routeIs('admin.users.*') ? 'users'
                    : (request()->routeIs('admin.settings*') ? 'settings'
                    : (request()->routeIs('admin.reports*') ? 'reports' : '')));
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $current === 'dashboard' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-slate-600 uppercase tracking-wider">Manajemen</p>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $current === 'users' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $current === 'reports' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-bar w-5 text-center"></i>
                <span>Laporan & Ekspor</span>
            </a>

            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-slate-600 uppercase tracking-wider">Sistem</p>

            <a href="{{ route('admin.settings') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $current === 'settings' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-sliders w-5 text-center"></i>
                <span>Konfigurasi Sistem</span>
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-900 shrink-0">
        <div class="flex items-center space-x-3 px-2 mb-3">
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400">Administrator</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center space-x-3 px-4 py-2.5 text-slate-400 hover:bg-red-500/10 hover:text-red-400 rounded-lg text-sm w-full transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
