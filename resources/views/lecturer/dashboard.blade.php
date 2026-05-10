@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    
    @php
    // Otak bunglon: ngecek yang login ini Dosen atau Mahasiswa
    $isLecturer = Auth::user()->role === 'lecturer'; 
    
    // Ganti kulit otomatis
    $bgSidebar = $isLecturer ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200';
    $textMenu = $isLecturer ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-[#0b276b]';
    $activeMenu = $isLecturer ? 'bg-amber-500 text-slate-900 shadow-[0_4px_0_0_#b45309]' : 'bg-[#0b276b] text-white shadow-[0_4px_0_0_#061a4f]';
@endphp

<aside class="w-64 {{ $bgSidebar }} border-r flex flex-col justify-between hidden md:flex shrink-0 z-20">
    <div class="flex-1 overflow-y-auto">
        <div class="h-20 flex items-center px-6 border-b {{ $isLecturer ? 'border-slate-800 bg-slate-900' : 'border-slate-100 bg-white' }} mb-4 sticky top-0 z-10">
            <div class="flex items-center space-x-3 {{ $isLecturer ? 'text-white' : 'text-[#0b276b]' }}">
                <div class="{{ $isLecturer ? 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)]' : 'bg-[#0b276b]' }} text-white p-2.5 rounded-xl shadow-lg">
                    <i class="fa-solid {{ $isLecturer ? 'fa-dragon' : 'fa-gamepad' }}"></i>
                </div>
                <h2 class="font-black text-xl tracking-wide uppercase">AlgoLearn</h2>
            </div>
        </div>

        <div class="px-6 mb-8 mt-2">
            <p class="text-[10px] font-black {{ $isLecturer ? 'text-slate-500' : 'text-slate-400' }} uppercase tracking-widest mb-2">
                {{ $isLecturer ? 'Role: Dosen' : 'Status Level' }}
            </p>
            <div class="inline-block">
                <p class="text-xs font-black {{ $isLecturer ? 'text-amber-500 bg-amber-500/10 border-amber-500/20' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }} py-2 px-4 rounded-xl uppercase tracking-wide border-2 shadow-sm flex items-center gap-2">
                    <i class="fa-solid {{ $isLecturer ? 'fa-shield-halved' : 'fa-medal' }}"></i> 
                    {{ $isLecturer ? 'Dosen Pengampu' : Auth::user()->getLevel() }}
                </p>
            </div>
        </div>

        <nav class="px-4 space-y-2 mb-4">
            @if($isLecturer)
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-satellite-dish w-6 text-center text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('lecturer.materials.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-boxes-stacked w-6 text-center text-lg"></i>
                    <span>Kelola Materi</span>
                </a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create', 'lecturer.materials.edit') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-hammer w-6 text-center text-lg"></i>
                    <span>Buat Materi</span>
                </a>
                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.students.progress') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-crosshairs w-6 text-center text-lg"></i>
                    <span>Laporan Mahasiswa</span>
                </a>
            @else
                <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-border-all w-6 text-center text-lg"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('student.material.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.material.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-book-open w-6 text-center text-lg"></i>
                    <span>Kurikulum</span>
                </a>
                <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-clipboard-list w-6 text-center text-lg"></i>
                    <span>Tugas Saya</span>
                </a>
                <a href="{{ route('student.progress.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.progress.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-chart-line w-6 text-center text-lg"></i>
                    <span>Laporan Progress</span>
                </a>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t {{ $isLecturer ? 'border-slate-800 bg-slate-900' : 'border-slate-100 bg-white' }} shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center space-x-3 px-4 py-4 {{ $isLecturer ? 'text-slate-500 hover:bg-red-900/30 hover:text-red-500' : 'text-slate-500 hover:bg-red-50 hover:text-red-600' }} rounded-2xl font-bold text-sm w-full transition-all group">
                <i class="fa-solid fa-power-off w-6 text-center text-lg group-hover:rotate-90 transition-transform"></i>
                <span>Keluar </span>
            </button>
        </form>
    </div>
</aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div>
                <h1 class="text-xl font-black text-slate-800 uppercase tracking-wide">Portal Dosen</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">{{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm animate-fade-in-up">
                    <h3 class="font-black text-slate-800 mb-6 flex items-center"><i class="fa-solid fa-chart-pie text-amber-500 mr-2"></i> Distribusi Level Player</h3>
                    
                    @php
                        // Hitungan dummy kalau belum ada di controller, biar keren dulu.
                        // Nanti lu bisa ganti logic ini di Controller Dashboard lu.
                        $pemula = 50; $menengah = 30; $lanjutan = 20; 
                    @endphp

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-slate-600">Level 1 - Pemula</span>
                                <span class="text-slate-800">{{ $pemula }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3">
                                <div class="bg-slate-400 h-3 rounded-full" style="width: {{ $pemula }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-blue-600">Level 2 - Menengah</span>
                                <span class="text-blue-800">{{ $menengah }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $menengah }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-emerald-600">Level 3 - Lanjutan</span>
                                <span class="text-emerald-800">{{ $lanjutan }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3">
                                <div class="bg-emerald-500 h-3 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: {{ $lanjutan }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800">Log Quiz Terbaru</h3>
                        </div>
                        <table class="w-full text-left border-collapse">
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($recentResults as $result)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-bold text-slate-700">{{ $result->user->name ?? 'Anonim' }}</td>
                                    <td class="px-6 py-4 text-slate-500 truncate max-w-[150px]">{{ $result->quiz->title ?? 'Kuis Dihapus' }}</td>
                                    <td class="px-6 py-4 font-black {{ $result->score >= ($result->quiz->passing_grade ?? 70) ? 'text-emerald-500' : 'text-red-500' }}">
                                        {{ $result->score }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 font-medium">Server sepi, belum ada player yang setor quest.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800">Arsip Materi</h3>
                            <a href="{{ route('lecturer.materials.create') }}" class="text-xs font-black text-slate-900 bg-amber-400 px-3 py-1.5 rounded-lg hover:bg-amber-500 shadow-sm"><i class="fa-solid fa-plus mr-1"></i> Bikin Baru</a>
                        </div>
                        <table class="w-full text-left border-collapse">
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($materials as $material)
                                <tr class="hover:bg-slate-50 group">
                                    <td class="px-6 py-4 font-bold text-slate-700">{{ $material->title }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-2 py-1 rounded uppercase tracking-wider">
                                            {{ $material->level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('lecturer.materials.edit', $material->id) }}" class="text-slate-400 hover:text-amber-500 transition-colors mr-2" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="{{ route('lecturer.quiz.create', $material->id) }}" class="text-slate-400 hover:text-emerald-500 transition-colors" title="Tambah Kuis">
                                            <i class="fa-solid fa-scroll"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada quest. Player lu butuh tantangan!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection