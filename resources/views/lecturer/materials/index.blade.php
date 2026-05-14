@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: none; } }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden">
    @php
        $isLecturer = Auth::user()->role === 'lecturer'; 
        
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
                <a href="{{ route('lecturer.pretest.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.pretest.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-scroll w-6 text-center text-lg"></i>
                    <span>Markas Pretest</span>
                </a>
                <a href="{{ route('lecturer.quiz.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-dungeon w-6 text-center text-lg"></i>
                    <span>Bank Kuis</span>
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

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b bg-white/80 backdrop-blur-md z-10">
            <h1 class="text-xl font-black text-slate-800 uppercase">Kelola Materi</h1>
            <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-white font-black">{{ substr(Auth::user()->name, 0, 1) }}</div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative">
            <div class="max-w-6xl mx-auto animate-fade-in-up">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Daftar Materi</h2>
                        <p class="text-slate-500 font-medium text-sm mt-1">Total: {{ $materials->count() }} Materi Terdeteksi.</p>
                    </div>
                    <a href="{{ route('lecturer.materials.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-black py-3 px-6 rounded-xl shadow-[0_4px_0_0_#b45309] hover:translate-y-[2px] hover:shadow-[0_2px_0_0_#b45309] transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-plus text-lg"></i>
                        <span>Tambah Materi</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-900 text-slate-300 text-xs uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-5">Judul Materi</th>
                                <th class="px-6 py-5">Level</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th class="px-6 py-5 text-right">Tindakan Dosen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-bold text-slate-700">
                            @forelse($materials as $material)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-5">{{ $material->title }}</td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1.5 rounded-md text-[10px] uppercase tracking-widest shadow-sm {{ $material->level == 'Lanjutan' ? 'bg-purple-100 text-purple-700 border border-purple-200' : ($material->level == 'Menengah' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200') }}">
                                        {{ $material->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    {!! $material->is_published ? '<span class="text-emerald-500 bg-emerald-50 px-3 py-1.5 rounded-md text-[10px] uppercase tracking-widest border border-emerald-100"><i class="fa-solid fa-circle-check mr-1"></i> Live</span>' : '<span class="text-slate-500 bg-slate-100 px-3 py-1.5 rounded-md text-[10px] uppercase tracking-widest border border-slate-200"><i class="fa-solid fa-file-lines mr-1"></i> Draft</span>' !!}
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end items-center space-x-4">
                                        <a href="{{ route('lecturer.materials.edit', $material->id) }}" class="text-amber-500 hover:text-amber-600 hover:scale-110 transition-all" title="Edit Materi">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>
                                        <a href="{{ route('lecturer.quiz.create', $material->id) }}" class="text-blue-500 hover:text-blue-600 hover:scale-110 transition-all" title="Kelola Kuis">
                                            <i class="fa-solid fa-clipboard-question text-lg"></i>
                                        </a>
                                        <form action="{{ route('lecturer.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Yakin mau musnahin materi ini beserta seluruh kuisnya?')" class="inline-block m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-500 hover:scale-110 transition-all" title="Hapus Materi">
                                                <i class="fa-solid fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-bold">
                                    <i class="fa-solid fa-ghost text-4xl mb-3"></i>
                                    <p>Belum ada materi yang dibikin. Santai amat jadi dosen!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection