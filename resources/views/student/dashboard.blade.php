@extends('layouts.app')
@section('content')
@php
    $user = Illuminate\Support\Facades\Auth::user();
    $results = \App\Models\QuizResult::with('quiz')->where('user_id', $user->id)->get();
    
    $passedQuizzes = $results->filter(function($result) {
        return $result->score >= ($result->quiz->passing_grade ?? 70);
    })->count();

    $totalQuizzes = \App\Models\Quiz::count();
    $totalMaterials = \App\Models\Material::count();

    $progress = $totalQuizzes > 0 ? round(($passedQuizzes / $totalQuizzes) * 100) : 0;
@endphp

<style>
    /* Animasi racikan rahasia */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    
    @keyframes float-tilt {
        0%, 100% { transform: translateY(0) rotate(-10deg); }
        50% { transform: translateY(-15px) rotate(0deg); }
    }
    .animate-float-tilt {
        animation: float-tilt 6s ease-in-out infinite;
    }

    @keyframes pulse-soft {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-pulse-soft {
        animation: pulse-soft 2s infinite ease-in-out;
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden">
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
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/50 backdrop-blur-md z-10 sticky top-0">
            <div class="flex items-center">
                <h1 class="text-xl font-black text-slate-800">Dashboard Mahasiswa</h1>
            </div>
            <div class="flex items-center space-x-6">
                <div class="w-10 h-10 rounded-full bg-[#0b276b] border-2 border-white shadow-md overflow-hidden flex items-center justify-center text-white font-bold cursor-pointer hover:scale-105 transition-transform">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-8">
            <div class="max-w-5xl mx-auto space-y-6">
                
                <!-- Banner Utama -->
                <div class="bg-[#0b276b] rounded-[2rem] p-8 sm:p-10 text-white shadow-xl relative overflow-hidden animate-fade-in-up" style="animation-delay: 0s;">
                    <div class="absolute right-0 top-0 opacity-20 text-[180px] -mt-16 -mr-16 animate-float-tilt pointer-events-none">
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
                    
                    <h2 class="text-3xl sm:text-4xl font-black mb-3 relative z-10">Welcome back, {{ Auth::user()->name }}! 🚀</h2>
                    <p class="text-blue-200 relative z-10 max-w-xl text-lg font-medium leading-relaxed">
                        Kerjakan kuis buat ningkatin levelmu. Jangan cuma dianggurin, ilmu itu dikejar, bukan ditungguin!
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Progress Card -->
                    <div class="bg-white p-6 sm:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm md:col-span-2 animate-fade-in-up hover:border-blue-200 transition-colors group" style="animation-delay: 0.1s;">
                        <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-[#0b276b] flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-route"></i>
                            </div>
                            Perjalanan Belajarmu
                        </h3>
                        
                        <div class="flex justify-between items-end mb-3">
                            <div>
                                <p class="text-4xl font-black text-[#0b276b]">{{ $progress }}%</p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Penyelesaian Kursus</p>
                            </div>
                            <div class="bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100">
                                <p class="text-sm font-bold text-emerald-600"><i class="fa-solid fa-check-circle mr-1"></i> {{ $passedQuizzes }} / {{ $totalQuizzes }} Lulus</p>
                            </div>
                        </div>
                        
                        <div class="w-full bg-slate-100 h-5 rounded-full overflow-hidden shadow-inner p-1">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000 ease-out relative shadow-sm" style="width: {{ $progress }}%">
                                <div class="w-full h-full bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-30 absolute"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="bg-white p-6 sm:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm flex flex-col justify-center items-center text-center animate-fade-in-up hover:border-blue-200 transition-colors group" style="animation-delay: 0.2s;">
                        <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-3xl mb-4 shadow-inner border-4 border-white group-hover:scale-110 transition-transform group-hover:bg-blue-500 group-hover:text-white">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <h4 class="text-4xl font-black text-slate-800">{{ $totalMaterials }}</h4>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Materi Tersedia</p>
                    </div>

                </div>

                <!-- CTA Card -->
                <div class="bg-white rounded-[2rem] border-2 border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 animate-fade-in-up hover:border-amber-200 transition-colors" style="animation-delay: 0.3s;">
                    <div class="text-center sm:text-left">
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Misi Selanjutnya</h3>
                        <p class="text-slate-500 font-medium">Ada evaluasi yang belum lu kerjain atau butuh diremedial nih. Gas sikat sekarang sebelum numpuk!</p>
                    </div>
                    <a href="{{ route('student.tasks.index') }}" class="shrink-0 w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-black text-lg py-4 px-8 rounded-2xl uppercase tracking-wider transition-all shadow-[0_6px_0_0_#d97706] hover:shadow-[0_2px_0_0_#d97706] hover:translate-y-[4px] flex items-center justify-center">
                        <i class="fa-solid fa-bolt mr-2"></i> Lihat Evaluasi
                    </a>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection