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
                <p class="text-[10px] font-black {{ $isLecturer ? 'text-slate-500' : 'text-slate-400' }} uppercase tracking-widest mb-2">Role: Dosen</p>
                <div class="inline-block">
                    <p class="text-xs font-black {{ $isLecturer ? 'text-amber-500 bg-amber-500/10 border-amber-500/20' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }} py-2 px-4 rounded-xl uppercase tracking-wide border-2 shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> DOSEN PENGAMPU
                    </p>
                </div>
            </div>

            <nav class="px-4 space-y-2 mb-4">
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-satellite-dish w-6 text-center text-lg"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('lecturer.materials.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-boxes-stacked w-6 text-center text-lg"></i><span>Kelola Materi</span>
                </a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create', 'lecturer.materials.edit') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-hammer w-6 text-center text-lg"></i><span>Buat Materi</span>
                </a>
                <a href="{{ route('lecturer.pretest.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.pretest.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-scroll w-6 text-center text-lg"></i><span>Markas Pretest</span>
                </a>
                <a href="{{ route('lecturer.quiz.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-dungeon w-6 text-center text-lg"></i><span>Bank Kuis</span>
                </a>
                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.students.progress') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-crosshairs w-6 text-center text-lg"></i><span>Laporan Mahasiswa</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t {{ $isLecturer ? 'border-slate-800 bg-slate-900' : 'border-slate-100 bg-white' }} shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-4 {{ $isLecturer ? 'text-slate-500 hover:bg-red-900/30 hover:text-red-500' : 'text-slate-500 hover:bg-red-50 hover:text-red-600' }} rounded-2xl font-bold text-sm w-full transition-all group">
                    <i class="fa-solid fa-power-off w-6 text-center text-lg group-hover:rotate-90 transition-transform"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8 animate-fade-in-up">
                    <h1 class="text-3xl font-black text-slate-800 mb-2 uppercase tracking-tight">Panel Progress</h1>
                    <p class="text-slate-500 font-medium">Pantau kelakuan mahasiswa ini. Kalau nilainya hancur karena sistem, kasih mereka ampunan reset pretest.</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in-up">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 text-xs uppercase tracking-wider">
                                <th class="px-6 py-5 font-black">Identitas Mahasiswa</th>
                                <th class="px-6 py-5 font-black text-center">Level Saat Ini</th>
                                <th class="px-6 py-5 font-black text-center">Status Pretest</th>
                                <th class="px-6 py-5 font-black text-center">Intervensi Dosen (Aksi)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($students as $student)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center font-black text-lg shrink-0">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $student->name }}</p>
                                        <p class="text-xs text-slate-400 font-mono">{{ $student->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($student->level == 'Lanjutan')
                                        <span class="px-3 py-1 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">Lanjutan</span>
                                    @elseif($student->level == 'Menengah')
                                        <span class="px-3 py-1 rounded-md text-[10px] font-black bg-blue-100 text-blue-700 border border-blue-200 uppercase">Menengah</span>
                                    @else
                                        <span class="px-3 py-1 rounded-md text-[10px] font-black bg-slate-100 text-slate-600 border border-slate-200 uppercase">Pemula</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($student->pretest_completed)
                                        <span class="text-emerald-500 font-bold text-xs"><i class="fa-solid fa-check-circle"></i> Selesai</span>
                                    @else
                                        <span class="text-amber-500 font-bold text-xs"><i class="fa-solid fa-clock"></i> Belum/Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                            <i class="fa-solid fa-eye"></i> Log
                                        </button>
                                        
                                        @if($student->pretest_completed)
                                        <form action="#" method="POST" onsubmit="return confirm('Lu yakin mau ngereset pretest bocah ini? Kasta dia bakal balik ke Pemula lho!');">
                                            @csrf
                                            <button type="submit" class="bg-red-50 hover:bg-red-500 text-red-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold border border-red-200 hover:border-red-500 transition-colors" title="Reset Uji Nyali">
                                                <i class="fa-solid fa-rotate-left"></i> Reset
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-bold">Belum ada player yang join ke server lu.</td>
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