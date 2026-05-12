@extends('layouts.app')
@section('content')
<style>
    @keyframes shake-door {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-8px) rotate(-2deg); }
        40% { transform: translateX(8px) rotate(2deg); }
        60% { transform: translateX(-8px) rotate(-2deg); }
        80% { transform: translateX(8px) rotate(2deg); }
    }
    .animate-shake-door {
        animation: shake-door 0.3s ease-in-out;
    }

    @keyframes pop-in-spring {
        0% { opacity: 0; transform: scale(0.5) translateY(20px); }
        50% { opacity: 1; transform: scale(1.05) translateY(-5px); }
        75% { transform: scale(0.95) translateY(2px); }
        100% { transform: scale(1) translateY(0); }
    }
    .animate-pop-in {
        animation: pop-in-spring 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ filter: 'semua', search: '', showModal: false, modalLevel: '', isShaking: null }">
    
    <aside class="w-64 bg-white border-r-2 border-slate-200 flex flex-col justify-between hidden md:flex shrink-0 z-20">
        <div class="flex-1 overflow-y-auto">
            
            <div class="h-20 flex items-center px-6 border-b-2 border-slate-100 mb-4 sticky top-0 bg-white/90 backdrop-blur-sm z-10">
                <div class="flex items-center space-x-3 text-[#0b276b]">
                    <div class="bg-[#0b276b] text-white p-2.5 rounded-xl shadow-[0_3px_0_0_#061a4f]">
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-xl tracking-wide">AlgoLearn</h2>
                    </div>
                </div>
            </div>

            <div class="px-6 mb-8 mt-2">
                <p class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Status Level Kamu</p>
                <div class="inline-block animate-pulse-soft">
                    <p class="text-sm font-black text-emerald-600 bg-emerald-50 py-2 px-4 rounded-xl uppercase tracking-wide border-2 border-emerald-100 shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-medal text-emerald-500"></i> {{ Auth::user()->getLevel() }}
                    </p>
                </div>
            </div>

            <nav class="px-4 space-y-2 mb-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('student.dashboard') ? 'bg-[#0b276b] text-white shadow-[0_4px_0_0_#061a4f] translate-y-[-2px]' : 'text-slate-600 hover:bg-slate-50 hover:text-[#0b276b]' }}">
                    <i class="fa-solid fa-border-all w-6 text-center text-lg {{ request()->routeIs('student.dashboard') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span>Beranda</span>
                </a>
                
                <a href="{{ route('student.material.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('student.material.*') ? 'bg-[#0b276b] text-white shadow-[0_4px_0_0_#061a4f] translate-y-[-2px]' : 'text-slate-600 hover:bg-slate-50 hover:text-[#0b276b]' }}">
                    <i class="fa-solid fa-book-open w-6 text-center text-lg {{ request()->routeIs('student.material.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span>Materi</span>
                </a>
                
                <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? 'bg-[#0b276b] text-white shadow-[0_4px_0_0_#061a4f] translate-y-[-2px]' : 'text-slate-600 hover:bg-slate-50 hover:text-[#0b276b]' }}">
                    <i class="fa-solid fa-clipboard-list w-6 text-center text-lg {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span>Latihan</span>
                </a>
                
                <a href="{{ route('student.progress.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('student.progress.index') ? 'bg-[#0b276b] text-white shadow-[0_4px_0_0_#061a4f] translate-y-[-2px]' : 'text-slate-600 hover:bg-slate-50 hover:text-[#0b276b]' }}">
                    <i class="fa-solid fa-chart-line w-6 text-center text-lg {{ request()->routeIs('student.progress.index') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span>Laporan Progres</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t-2 border-slate-100 space-y-1 bg-white shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-4 text-slate-500 hover:bg-red-50 hover:text-red-600 rounded-2xl font-bold text-sm w-full transition-colors group">
                    <i class="fa-solid fa-arrow-right-from-bracket w-6 text-center text-lg group-hover:-translate-x-1 transition-transform"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden relative">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/50 backdrop-blur-sm z-10">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-slate-800">Kurikulum Materi</h1>
            </div>
            <div class="flex items-center space-x-6">
                <div class="w-10 h-10 rounded-full bg-[#0b276b] border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto">
                
                <div class="mb-8 animate-fade-in-up">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Kurikulum AlgoLearn</h1>
                    <p class="text-slate-500">Pilih materi yang ingin lu pelajari. Materi dengan ikon gembok harus dibuka dengan menyelesaikan level lu saat ini.</p>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in-up">
                    <div class="flex space-x-2">
                        <button @click="filter = 'semua'" :class="filter === 'semua' ? 'bg-[#0b276b] text-white shadow-md border-transparent' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all border">Semua</button>
                        <button @click="filter = 'pemula'" :class="filter === 'pemula' ? 'bg-[#0b276b] text-white shadow-md border-transparent' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all border">Pemula</button>
                        <button @click="filter = 'menengah'" :class="filter === 'menengah' ? 'bg-[#0b276b] text-white shadow-md border-transparent' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all border">Menengah</button>
                        <button @click="filter = 'lanjutan'" :class="filter === 'lanjutan' ? 'bg-[#0b276b] text-white shadow-md border-transparent' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all border">Lanjutan</button>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input x-model="search" type="text" class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0b276b]/20 w-full md:w-64" placeholder="Cari materi...">
                    </div>
                </div>

                @php
                    $levelScores = ['Pemula' => 1, 'Menengah' => 2, 'Lanjutan' => 3];
                    // KUNCINYA DI SINI: Panggil ->level murni dari DB, bukan fungsi yang kepanjangan!
                    $userScore = $levelScores[Auth::user()->level] ?? 1;
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up">
                    @forelse($materials as $item)
                    @php
                        $matScore = $levelScores[$item->level] ?? 1;
                        // Kalau skor kasta user >= skor materi, gembok terbuka!
                        $isLocked = $matScore > $userScore;
                    @endphp

                    <div x-show="(filter === 'semua' || '{{ strtolower($item->level) }}'.includes(filter)) && '{{ addslashes(strtolower($item->title)) }}'.includes(search.toLowerCase())" 
                         x-transition.opacity.duration.300ms 
                         :class="isShaking === {{ $item->id }} ? 'animate-shake-door border-red-400 shadow-lg shadow-red-100 z-20' : '{{ $isLocked ? 'border-slate-200 opacity-80' : 'border-slate-200 shadow-sm hover:-translate-y-1 hover:shadow-xl z-0' }}'"
                         class="bg-white rounded-xl border overflow-hidden flex flex-col group transition-all duration-300 relative">
                        
                        <div class="h-40 bg-slate-800 relative overflow-hidden">
                            <div class="absolute top-3 left-3 z-10">
                                <span class="bg-white/95 {{ $isLocked ? 'text-slate-400' : 'text-[#0b276b]' }} text-[10px] font-extrabold px-2 py-1 rounded shadow-sm uppercase tracking-tighter">
                                    {{ $item->level }}
                                </span>
                            </div>
                            
                            @if($isLocked)
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center text-white cursor-pointer" 
                                 @click="isShaking = {{ $item->id }}; showModal = true; modalLevel = '{{ $item->level }}'; setTimeout(() => { isShaking = null; }, 300)">
                                <div class="w-12 h-12 rounded-full bg-slate-800/80 border border-slate-600 flex items-center justify-center mb-2 shadow-lg group-hover:bg-red-500/20 group-hover:border-red-500/50 transition-all">
                                    <i class="fa-solid fa-lock text-slate-300 group-hover:text-red-400"></i>
                                </div>
                            </div>
                            @endif

                            <div class="w-full h-full opacity-40 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] {{ !$isLocked ? 'group-hover:scale-110 transition-transform duration-700' : '' }}"></div>
                            
                            @if(!$isLocked)
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('student.material.show', $item->id) }}" class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 shadow-lg scale-90 group-hover:scale-100 transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-5 flex flex-col flex-grow relative z-10">
                            <h3 class="font-bold {{ $isLocked ? 'text-slate-500' : 'text-slate-800 group-hover:text-[#0b276b]' }} mb-3 leading-snug transition-colors line-clamp-2">{{ $item->title }}</h3>
                            
                            <div class="flex items-center space-x-3 text-[11px] {{ $isLocked ? 'text-slate-400' : 'text-slate-500' }} mb-6">
                                <span class="flex items-center space-x-1"><i class="fa-solid fa-video {{ $isLocked ? '' : 'text-red-500' }}"></i> <span>Video</span></span>
                                <span class="flex items-center space-x-1"><i class="fa-solid fa-file-lines {{ $isLocked ? '' : 'text-blue-500' }}"></i> <span>Modul</span></span>
                                <span class="flex items-center space-x-1"><i class="fa-solid fa-circle-check {{ $isLocked ? '' : 'text-emerald-500' }}"></i> <span>Kuis</span></span>
                            </div>
                            
                            <div class="mt-auto pt-4 border-t border-slate-50">
                                @if($isLocked)
                                <button @click="isShaking = {{ $item->id }}; showModal = true; modalLevel = '{{ $item->level }}'; setTimeout(() => { isShaking = null; }, 300)" class="w-full py-2.5 bg-slate-50 text-slate-400 border border-slate-200 rounded-lg text-xs font-bold flex items-center justify-center space-x-2 transition-all cursor-pointer hover:bg-red-50 hover:text-red-500 hover:border-red-200">
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                    <span>Materi Terkunci</span>
                                </button>
                                @else
                                <a href="{{ route('student.material.show', $item->id) }}" class="w-full py-2.5 bg-[#ebf0fc] hover:bg-[#0b276b] text-[#0b276b] hover:text-white border border-transparent rounded-lg text-xs font-bold transition-all flex items-center justify-center space-x-2">
                                    <span>Buka Materi</span>
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 text-3xl mb-4">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Materi Belum Tersedia</h3>
                        <p class="text-slate-500 max-w-sm text-sm">Kurikulum untuk level lu sedang dalam tahap penyusunan oleh Admin.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>
        
        <div x-show="showModal" style="display: none;" class="absolute inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false" x-transition.opacity.duration.300ms></div>
            
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 relative z-10 shadow-2xl" 
                 @click.stop 
                 x-show="showModal"
                 x-transition:enter="animate-pop-in" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-90">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 border-4 border-white shadow-lg -mt-12 animate-bounce">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Akses Ditolak!</h3>
                <p class="text-sm text-slate-500 text-center mb-6 leading-relaxed">Sabar dawg! Ini materi untuk <span x-text="modalLevel" class="font-bold text-red-500 uppercase tracking-wide"></span>. Selesaikan dulu kuis di levelmu saat ini biar bisa naik kasta.</p>
                <button @click="showModal = false" class="w-full bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-700 font-bold py-3 rounded-xl transition-colors border border-slate-200 hover:border-red-200">
                    Siap, Gue Paham!
                </button>
            </div>
        </div>

    </main>
</div>
@endsection