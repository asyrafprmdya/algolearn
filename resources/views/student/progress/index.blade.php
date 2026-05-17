@extends('layouts.app')
@section('content')

@php
    $totalAttempts = $results->count();
    $progressPercentage = $totalAttempts > 0 ? round(($passedQuizzes / $totalAttempts) * 100) : 0;
@endphp

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
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/50 backdrop-blur-sm z-10">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-slate-800">Laporan Progres</h1>
            </div>
            <div class="flex items-center space-x-6">
                <div class="w-10 h-10 rounded-full bg-[#0b276b] border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Transkrip Nilai Sementara</h2>
                        <p class="text-slate-500 text-sm mt-1">Pantau perkembangan belajarmu di sini. Jangan dibiarin merah semua!</p>
                    </div>
                    <div class="text-right shrink-0 bg-slate-50 px-6 py-4 rounded-xl border border-slate-100">
                        <p class="text-3xl font-black text-[#0b276b]">{{ $progressPercentage }}%</p>
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Penguasaan</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200">
                                <th class="px-6 py-4">Materi & Kuis</th>
                                <th class="px-6 py-4">KKM</th>
                                <th class="px-6 py-4">Skor Akhir</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($results as $result)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-base">{{ $result->quiz->title ?? 'Kuis Dihapus' }}</p>
                                    <p class="text-xs text-slate-400">{{ $result->quiz->material->title ?? 'Materi Dihapus' }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-bold">{{ $result->quiz->passing_grade ?? 70 }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-xl {{ $result->is_passed ? 'text-emerald-500' : 'text-red-500' }}">
                                        {{ $result->score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($result->is_passed)
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-3 py-1.5 rounded-md uppercase tracking-wider"><i class="fa-solid fa-check mr-1"></i> Tuntas</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-3 py-1.5 rounded-md uppercase tracking-wider"><i class="fa-solid fa-fire mr-1"></i> Remedial</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-slate-300 text-4xl mb-3"><i class="fa-solid fa-folder-open"></i></div>
                                    <p class="text-slate-500 font-medium">Belum ada riwayat penderitaan. Kerjain kuis dulu sana!</p>
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