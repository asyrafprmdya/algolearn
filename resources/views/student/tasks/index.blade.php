@extends('layouts.app')
@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    @keyframes pulse-soft {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-pulse-soft {
        animation: pulse-soft 2s infinite ease-in-out;
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden font-sans">
    
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
                <p class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Status Kasta Lu</p>
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
                    <span>Kurikulum</span>
                </a>
                
                <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? 'bg-[#0b276b] text-white shadow-[0_4px_0_0_#061a4f] translate-y-[-2px]' : 'text-slate-600 hover:bg-slate-50 hover:text-[#0b276b]' }}">
                    <i class="fa-solid fa-clipboard-list w-6 text-center text-lg {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span>Tugas Saya</span>
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
                    <span>Keluar Game</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden relative">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b-2 border-slate-200 bg-white/80 backdrop-blur-md z-10 sticky top-0">
            <div class="flex items-center">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Misi 🎯</h1>
            </div>
            <div class="flex items-center space-x-6">
                <div class="w-12 h-12 rounded-full bg-[#0b276b] border-4 border-white shadow-md overflow-hidden flex items-center justify-center text-white font-black text-lg cursor-pointer hover:scale-105 transition-transform">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                
                <div class="mb-10 animate-fade-in-up">
                    <p class="text-slate-500 font-medium text-lg">Selesaikan semua misi evaluasi di bawah buat buktiin kalau lu pantes naik kasta.</p>
                </div>

                @forelse($quizzes as $index => $quiz)
                    @php
                        $isCompleted = isset($results[$quiz->id]);
                        $score = $results[$quiz->id] ?? 0;
                        $isPassed = $score >= $quiz->passing_grade;
                    @endphp

                    <div class="bg-white rounded-[2rem] border-2 border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-blue-200 transition-colors animate-fade-in-up group" style="animation-delay: {{ $index * 0.1 }}s;">
                        
                        <div class="flex items-start sm:items-center space-x-5 sm:space-x-6">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shrink-0 border-2 transition-transform group-hover:scale-110 
                                {{ $isCompleted ? ($isPassed ? 'bg-emerald-50 text-emerald-500 border-emerald-200 shadow-[0_4px_0_0_#a7f3d0]' : 'bg-red-50 text-red-500 border-red-200 shadow-[0_4px_0_0_#fecaca]') : 'bg-blue-50 text-[#0b276b] border-blue-200 shadow-[0_4px_0_0_#bfdbfe]' }}">
                                <i class="fa-solid {{ $isCompleted ? ($isPassed ? 'fa-check-double' : 'fa-skull-crossbones') : 'fa-bolt' }}"></i>
                            </div>
                            
                            <div>
                                <p class="text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest"><i class="fa-solid fa-book text-slate-300 mr-1"></i> {{ $quiz->material->title }}</p>
                                <h3 class="text-xl font-black text-slate-800 mb-3 group-hover:text-[#0b276b] transition-colors">{{ $quiz->title }}</h3>
                                
                                <div class="flex flex-wrap items-center gap-3 text-sm font-bold">
                                    <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-lg border border-amber-200">
                                        <i class="fa-solid fa-bullseye mr-1"></i> KKM: {{ $quiz->passing_grade }}
                                    </span>
                                    
                                    @if($isCompleted)
                                        <span class="px-3 py-1 rounded-lg border {{ $isPassed ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                                            <i class="fa-solid {{ $isPassed ? 'fa-award' : 'fa-fire' }} mr-1"></i> Skor: {{ $score }}
                                        </span>
                                    @else
                                        <span class="bg-slate-50 text-slate-400 px-3 py-1 rounded-lg border border-slate-200">
                                            <i class="fa-regular fa-clock mr-1"></i> Belum Dikerjakan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0 flex flex-col gap-3 w-full md:w-auto mt-4 md:mt-0 pt-6 md:pt-0 border-t-2 border-slate-100 md:border-0">
                            @if($isCompleted)
                                @if($isPassed)
                                    <button disabled class="w-full md:w-auto px-8 py-4 bg-slate-50 text-emerald-500 font-black text-lg rounded-2xl cursor-not-allowed border-2 border-slate-200 flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-lock"></i>
                                        <span>Tuntas</span>
                                    </button>
                                @else
                                    <a href="{{ route('student.quiz.show', $quiz->id) }}" class="block w-full md:w-auto px-8 py-4 bg-red-500 hover:bg-red-600 text-white font-black text-lg rounded-2xl uppercase tracking-wider transition-all text-center shadow-[0_6px_0_0_#991b1b] hover:shadow-[0_2px_0_0_#991b1b] hover:translate-y-[4px] animate-pulse-soft flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-rotate-right"></i>
                                        <span>Remedial!</span>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('student.quiz.show', $quiz->id) }}" class="block w-full md:w-auto px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black text-lg rounded-2xl uppercase tracking-wider transition-all text-center shadow-[0_6px_0_0_#b45309] hover:shadow-[0_2px_0_0_#b45309] hover:translate-y-[4px] flex items-center justify-center space-x-2">
                                    <span>Gas Kuis</span>
                                    <i class="fa-solid fa-play"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-24 flex flex-col items-center justify-center text-center bg-white rounded-[2rem] border-2 border-slate-200 border-dashed animate-fade-in-up">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-5xl mb-6 shadow-inner">
                            <i class="fa-solid fa-mug-hot"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Misi Kosong Lek!</h3>
                        <p class="text-slate-500 max-w-sm text-lg font-medium">Belum ada bos yang harus lu kalahin di level ini. Mending ngopi dulu.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </main>
</div>
@endsection