@extends('layouts.app')
@section('content')
<style>
    .path-line { position: absolute; left: 50%; width: 6px; background: #e2e8f0; z-index: 0; transform: translateX(-50%); }
    .level-node { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .level-node:hover { transform: scale(1.15); }
    .locked { filter: grayscale(1); opacity: 0.5; cursor: not-allowed; }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .floating { animation: float 3s ease-in-out infinite; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ showLevelUp: false }">
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
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b-2 border-slate-100 bg-white/80 backdrop-blur-md z-10">
            <h1 class="text-xl font-black text-[#0b276b] uppercase tracking-tight">Arena Latihan</h1>
            <div class="flex items-center space-x-3">
                <div class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-xs font-black uppercase">🔥 5 Day Streak</div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 bg-slate-50">
            <div class="max-w-xl mx-auto relative pb-32">
                @php
                    $userLevelStr = strtolower(Auth::user()->level ?? 'pemula');
                    $userRank = 1;
                    if (str_contains($userLevelStr, 'menengah')) $userRank = 2;
                    if (str_contains($userLevelStr, 'lanjutan')) $userRank = 3;

                    $sections = [
                        'Pemula' => ['color' => 'emerald', 'bg' => 'bg-emerald-500', 'border' => 'border-emerald-700', 'icon' => 'fa-seedling', 'rank' => 1],
                        'Menengah' => ['color' => 'blue', 'bg' => 'bg-blue-500', 'border' => 'border-blue-700', 'icon' => 'fa-fire', 'rank' => 2],
                        'Lanjutan' => ['color' => 'purple', 'bg' => 'bg-purple-500', 'border' => 'border-purple-700', 'icon' => 'fa-crown', 'rank' => 3]
                    ];
                    
                    $canUnlockNextNode = true; 
                @endphp

                @foreach($sections as $levelName => $theme)
                    @php
                        $isSectionLocked = $theme['rank'] > $userRank;
                        $headerBg = $isSectionLocked ? 'bg-slate-400' : $theme['bg'];
                        $headerBorder = $isSectionLocked ? 'border-slate-500' : $theme['border'];
                        $headerIcon = $isSectionLocked ? 'fa-lock' : $theme['icon'];
                    @endphp

                    <div class="mb-24 relative">
                        <div class="text-center mb-16 relative z-10">
                            <span class="px-8 py-3 {{ $headerBg }} text-white font-black rounded-2xl shadow-xl uppercase tracking-widest text-sm border-b-4 {{ $headerBorder }} {{ $isSectionLocked ? 'opacity-70' : '' }}">
                                <i class="fa-solid {{ $headerIcon }} mr-2"></i> Unit: {{ $levelName }}
                            </span>
                        </div>

                        <div class="relative flex flex-col items-center space-y-16">
                            @php 
                                $units = $materials[$levelName] ?? collect([]);
                            @endphp

                            @forelse($units as $index => $unit)
                                @php
                                    $completedArray = is_array($completedMaterialIds) ? $completedMaterialIds : [];
                                    $isDone = in_array($unit->id, $completedArray);
                                    
                                    $isLocked = $isSectionLocked || !$canUnlockNextNode;
                                    
                                    if (!$isDone) {
                                        $canUnlockNextNode = false;
                                    }

                                    $zigzag = ($index % 2 == 0) ? 'mr-24' : 'ml-24';
                                    
                                    $quiz = $unit->quizzes->first();
                                @endphp

                                <div class="relative z-10 {{ $zigzag }}">
                                    @if($isLocked)
                                        <div class="level-node locked w-24 h-24 bg-slate-200 rounded-full border-b-8 border-slate-300 flex items-center justify-center shadow-lg">
                                            <i class="fa-solid fa-lock text-slate-400 text-3xl"></i>
                                        </div>
                                    @else
                                        <div class="relative">
                                            @if($quiz)
                                                <a href="{{ route('student.quiz.show', $quiz->id) }}" 
                                                   class="level-node w-24 h-24 rounded-full border-b-8 flex items-center justify-center shadow-xl transition-all
                                                   {{ $isDone ? 'bg-emerald-500 border-emerald-700' : $theme['bg'].' '.$theme['border'] }}">
                                                    <i class="fa-solid {{ $isDone ? 'fa-check' : 'fa-star' }} text-white text-3xl"></i>
                                                </a>
                                            @else
                                                <div class="level-node w-24 h-24 bg-slate-300 rounded-full border-b-8 border-slate-400 flex items-center justify-center shadow-lg cursor-not-allowed" onclick="alert('Sabar lek! GM belum nyiapin kuis buat materi ini.')">
                                                    <i class="fa-solid fa-person-digging text-slate-500 text-3xl"></i>
                                                </div>
                                            @endif
                                            <div class="absolute top-1/2 {{ ($index % 2 == 0) ? 'left-full ml-6' : 'right-full mr-6' }} -translate-y-1/2 bg-white px-4 py-2 rounded-2xl border-2 border-slate-200 shadow-sm whitespace-nowrap">
                                                <p class="text-sm font-black text-slate-800">{{ $unit->title }}</p>
                                                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-1">
                                                    <div class="{{ $isDone ? 'w-full' : 'w-0' }} bg-emerald-500 h-1.5 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="bg-white p-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-sm">Belum ada latihan di kasta ini.</div>
                            @endforelse

                            @if($units->count() > 0)
                                <div class="path-line top-12 bottom-0"></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div x-show="showLevelUp" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0b276b]/90 backdrop-blur-md p-6" style="display: none;" x-transition>
            <div class="text-center">
                <div class="floating mb-6"><i class="fa-solid fa-trophy text-8xl text-amber-400"></i></div>
                <h2 class="text-5xl font-black text-white uppercase mb-2">Kasta Terlampaui!</h2>
                <p class="text-amber-200 font-bold mb-8 text-xl">Lanjutin perjuangan lu ke kasta berikutnya lek!</p>
                <button @click="showLevelUp = false" class="bg-white text-[#0b276b] font-black px-12 py-4 rounded-2xl shadow-[0_6px_0_0_#cbd5e1] hover:translate-y-[2px] transition-all uppercase">Gaskeun!</button>
            </div>
        </div>
    </main>
</div>
@endsection