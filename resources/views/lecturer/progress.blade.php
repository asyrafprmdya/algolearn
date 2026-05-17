@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: none; } }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ openLog: null, confirmReset: null }">
    
    @if(session('success'))
    <div x-data="{ showToast: true }" 
         x-show="showToast" 
         x-init="setTimeout(() => showToast = false, 4000)"
         class="fixed top-8 left-1/2 -translate-x-1/2 z-[10000] flex items-center gap-4 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-[0_10px_40px_-10px_rgba(16,185,129,0.7)]"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-10 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-10 scale-90">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-check-double text-xl"></i>
        </div>
        <div>
            <h4 class="font-black text-sm uppercase tracking-widest">Eksekusi Berhasil!</h4>
            <p class="text-xs font-bold text-emerald-100">{{ session('success') }}</p>
        </div>
        <button type="button" @click="showToast = false" class="ml-2 text-emerald-200 hover:text-white active:scale-90 transition-all">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>
    @endif

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
                    Role System
                </p>
                <div class="inline-block">
                    <p class="text-xs font-black {{ $isLecturer ? 'text-amber-500 bg-amber-500/10 border-amber-500/20' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }} py-2 px-4 rounded-xl uppercase tracking-wide border-2 shadow-sm flex items-center gap-2">
                        <i class="fa-solid {{ $isLecturer ? 'fa-shield-halved' : 'fa-medal' }}"></i> 
                        {{ $isLecturer ? 'GM MODE' : Auth::user()->getLevel() }}
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
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
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

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100 relative">
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10 sticky top-0">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">GM {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-6xl mx-auto animate-fade-in-up">
                
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Laporan Mahasiswa</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Pantau gerak-gerik maba lu di sini. Siksa yang males, apresiasi yang rajin.</p>
                </div>

                <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b-2 border-slate-200">
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest w-16">ID</th>
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest">Identitas Maba</th>
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Kasta Saat Ini</th>
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Status Pretest</th>
                                    <th class="p-5 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Aksi GM</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($students as $student)
                                    @php
                                        $levelStr = strtolower($student->level ?? '');
                                        $hasPretest = !empty($levelStr) && $levelStr !== 'belum' && $levelStr !== 'belum pretest';
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="p-5 text-center">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm mx-auto shadow-sm border border-slate-200">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <p class="font-black text-slate-800 text-sm">{{ $student->name }}</p>
                                            <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $student->email }}</p>
                                        </td>
                                        <td class="p-5 text-center">
                                            @if($hasPretest)
                                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg uppercase tracking-widest border border-indigo-200 shadow-sm">
                                                    {{ $student->level }}
                                                </span>
                                            @else
                                                <span class="text-slate-300 text-xl"><i class="fa-solid fa-minus"></i></span>
                                            @endif
                                        </td>
                                        <td class="p-5 text-center">
                                            @if($hasPretest)
                                                <div class="inline-flex items-center px-3 py-1.5 bg-emerald-50 border-2 border-emerald-100 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                                                    <i class="fa-solid fa-circle-check mr-1.5 text-emerald-500"></i> Selesai
                                                </div>
                                            @else
                                                <div class="inline-flex items-center px-3 py-1.5 bg-amber-50 border-2 border-amber-100 text-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                                                    <i class="fa-solid fa-clock mr-1.5 text-amber-500"></i> Belum/Menunggu
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-5">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" @click="openLog = {{ $student->id }}" class="px-4 py-2 bg-white border-2 border-slate-200 text-slate-600 hover:border-sky-500 hover:text-sky-600 hover:bg-sky-50 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                                                    <i class="fa-solid fa-eye"></i> Log
                                                </button>
                                                
                                                <form id="reset-form-{{ $student->id }}" action="{{ route('lecturer.students.reset', $student->id) }}" method="POST">
                                                    @csrf
                                                    <button type="button" @click="confirmReset = {{ $student->id }}" class="px-4 py-2 bg-white border-2 border-slate-200 text-slate-600 hover:border-red-500 hover:text-red-600 hover:bg-red-50 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                                                        <i class="fa-solid fa-rotate-left"></i> Reset
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-10 text-center">
                                            <div class="text-slate-300 mb-3"><i class="fa-solid fa-ghost text-5xl"></i></div>
                                            <p class="text-slate-500 font-bold">Daftar maba masih kosong. Kelas lu sepi amat lek!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        @foreach($students as $student)
            <div x-show="openLog === {{ $student->id }}" 
                 style="display: none;"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                
                <div class="bg-slate-50 rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col relative shadow-2xl border-4 border-slate-300 overflow-hidden" @click.away="openLog = null" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    <div class="px-6 py-5 bg-white border-b-2 border-slate-200 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#0b276b] text-white flex items-center justify-center font-black shadow-md border-2 border-blue-200">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight leading-tight">Log: {{ $student->name }}</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">Memantau riwayat ujian dan penderitaan</p>
                            </div>
                        </div>
                        <button type="button" @click="openLog = null" class="w-10 h-10 bg-slate-100 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                        @php
                            $logs = \App\Models\QuizResult::with(['quiz.material'])->where('user_id', $student->id)->latest()->get();
                        @endphp

                        @if($logs->isEmpty())
                            <div class="text-center py-12">
                                <i class="fa-solid fa-mug-hot text-6xl text-slate-300 mb-4"></i>
                                <h4 class="font-black text-slate-500 uppercase tracking-widest">Belum Ada Riwayat</h4>
                                <p class="text-sm font-bold text-slate-400 mt-2">Maba ini murni belum nyentuh kuis sama sekali. Tegur gih!</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($logs as $log)
                                    <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center hover:border-sky-300 transition-colors">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-widest border border-slate-200">
                                                    {{ $log->quiz->category === 'evaluation' ? 'Evaluasi Pop-up' : 'Arena Latihan' }}
                                                </span>
                                                <span class="text-[10px] font-bold text-slate-400"><i class="fa-regular fa-clock"></i> {{ $log->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                            <h4 class="font-bold text-slate-700 text-sm leading-tight">{{ $log->quiz->title ?? 'Kuis Telah Dihapus' }}</h4>
                                            <p class="text-xs font-bold text-slate-400 mt-1">Materi: {{ $log->quiz->material->title ?? 'Unknown' }}</p>
                                        </div>
                                        
                                        <div class="flex items-center gap-4 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 shrink-0 w-full sm:w-auto">
                                            <div class="text-center">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Skor</p>
                                                <p class="font-black text-lg leading-none {{ $log->score >= ($log->quiz->passing_grade ?? 0) ? 'text-emerald-600' : 'text-red-600' }}">{{ $log->score }}</p>
                                            </div>
                                            <div class="w-px h-8 bg-slate-200"></div>
                                            <div>
                                                @if($log->is_passed)
                                                    <span class="inline-flex items-center text-emerald-600 font-black text-xs uppercase tracking-widest">
                                                        <i class="fa-solid fa-check-circle mr-1"></i> Lulus
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center text-red-600 font-black text-xs uppercase tracking-widest">
                                                        <i class="fa-solid fa-skull mr-1"></i> Gagal
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div x-show="confirmReset !== null" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" 
             style="display: none;" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-3xl max-w-sm w-full p-8 relative shadow-2xl border-4 border-red-500 text-center" 
                 @click.away="confirmReset = null" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg rotate-12">
                    <i class="fa-solid fa-triangle-exclamation text-4xl -rotate-12"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Yakin Mau Reset?</h3>
                <p class="text-slate-500 font-bold mb-8 text-sm leading-relaxed">Semua dosa dan keringat maba ini bakal dihapus permanen. Dia bakal balik lagi jadi cupu kasta terbawah. Yakin lek?</p>
                
                <div class="flex flex-col gap-3">
                    <button type="button" @click="document.getElementById('reset-form-' + confirmReset).submit()" class="w-full py-4 bg-red-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#b91c1c] hover:translate-y-[2px] hover:shadow-[0_2px_0_0_#b91c1c] active:translate-y-[4px] active:shadow-none transition-all text-xs">
                        Ya, Reset Sekarang!
                    </button>
                    <button type="button" @click="confirmReset = null" class="w-full py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-all text-xs">
                        Batalin Aja
                    </button>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection