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
                        {{ $isLecturer ? 'GM MODE' : Auth::user()->getLevel() }}
                    </p>
                </div>
            </div>

            <nav class="px-4 space-y-2 mb-4">
                @if($isLecturer)
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
                @endif
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
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">GM {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative">
            <div class="max-w-6xl mx-auto animate-fade-in-up">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Kelola Materi</h1>
                        <p class="text-slate-500 font-medium text-sm mt-1">Daftar amunisi buat nyiksa maba. Jangan lupa bikinin kuisnya!</p>
                    </div>
                    <a href="{{ route('lecturer.materials.create') }}" class="px-6 py-3 bg-amber-500 text-white font-black uppercase tracking-widest rounded-xl shadow-[0_4px_0_0_#b45309] hover:translate-y-[2px] hover:shadow-none transition-all active:scale-95 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-plus"></i> <span>Materi Baru</span>
                    </a>
                </div>

                <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b-2 border-slate-200">
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest">Judul Materi</th>
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest">Kasta</th>
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Aksi GM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $material)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="p-5">
                                        <p class="font-black text-slate-800 text-lg">{{ $material->title }}</p>
                                    </td>
                                    <td class="p-5">
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg uppercase tracking-widest">
                                            {{ $material->level }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center justify-center gap-2">
                                            
                                            <a href="{{ route('lecturer.quiz.create', ['material' => $material->id, 'category' => 'practice']) }}" class="px-4 py-2 bg-emerald-50 border-2 border-emerald-200 text-emerald-600 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                                <i class="fa-solid fa-gamepad mr-1"></i> Bikin Evaluasi
                                            </a>
                                            
                                            <a href="{{ route('lecturer.quiz.create', ['material' => $material->id, 'category' => 'evaluation']) }}" class="px-4 py-2 bg-amber-50 border-2 border-amber-200 text-amber-600 hover:bg-amber-500 hover:text-white hover:border-amber-500 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                                <i class="fa-solid fa-star mr-1"></i> Bikin Latihan
                                            </a>
                                            
                                            <a href="{{ route('lecturer.materials.edit', $material->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form action="{{ route('lecturer.materials.destroy', $material->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin mau ngehapus materi ini lek?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                
                                @if($materials->isEmpty())
                                <tr>
                                    <td colspan="3" class="p-10 text-center">
                                        <div class="text-slate-300 mb-3"><i class="fa-solid fa-box-open text-5xl"></i></div>
                                        <p class="text-slate-500 font-bold">Materi masih kosong, gas bikin amunisi baru!</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection