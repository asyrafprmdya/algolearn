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
                    <div class="{{ $isLecturer ? 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)]' : 'bg-[#0b276b]' }} text-white p-2.5 rounded-xl shadow-lg"><i class="fa-solid {{ $isLecturer ? 'fa-dragon' : 'fa-gamepad' }}"></i></div>
                    <h2 class="font-black text-xl tracking-wide uppercase">AlgoLearn</h2>
                </div>
            </div>
            <div class="px-6 mb-8 mt-2">
                <p class="text-[10px] font-black {{ $isLecturer ? 'text-slate-500' : 'text-slate-400' }} uppercase tracking-widest mb-2">{{ $isLecturer ? 'Role: Dosen' : 'Status Level' }}</p>
                <div class="inline-block">
                    <p class="text-xs font-black {{ $isLecturer ? 'text-amber-500 bg-amber-500/10 border-amber-500/20' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }} py-2 px-4 rounded-xl uppercase tracking-wide border-2 shadow-sm flex items-center gap-2">
                        <i class="fa-solid {{ $isLecturer ? 'fa-shield-halved' : 'fa-medal' }}"></i> {{ $isLecturer ? 'Dosen Pengampu' : Auth::user()->getLevel() }}
                    </p>
                </div>
            </div>
            <nav class="px-4 space-y-2 mb-4">
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}"><i class="fa-solid fa-satellite-dish w-6 text-center text-lg"></i><span>Dashboard</span></a>
                <a href="{{ route('lecturer.materials.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}"><i class="fa-solid fa-boxes-stacked w-6 text-center text-lg"></i><span>Kelola Materi</span></a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create', 'lecturer.materials.edit') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}"><i class="fa-solid fa-hammer w-6 text-center text-lg"></i><span>Buat Materi</span></a>
                <a href="{{ route('lecturer.pretest.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.pretest.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}"><i class="fa-solid fa-scroll w-6 text-center text-lg"></i><span>Markas Pretest</span></a>
                <a href="{{ route('lecturer.quiz.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}"><i class="fa-solid fa-dungeon w-6 text-center text-lg"></i><span>Bank Kuis</span></a>
                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.students.progress') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}"><i class="fa-solid fa-crosshairs w-6 text-center text-lg"></i><span>Laporan Mahasiswa</span></a>
            </nav>
        </div>
        <div class="p-4 border-t {{ $isLecturer ? 'border-slate-800 bg-slate-900' : 'border-slate-100 bg-white' }} shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-4 {{ $isLecturer ? 'text-slate-500 hover:bg-red-900/30 hover:text-red-500' : 'text-slate-500 hover:bg-red-50 hover:text-red-600' }} rounded-2xl font-bold text-sm w-full transition-all group"><i class="fa-solid fa-power-off w-6 text-center text-lg group-hover:rotate-90 transition-transform"></i><span>Keluar</span></button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div><h1 class="text-xl font-black text-slate-800 uppercase tracking-wide">Preview Kuis</h1></div>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">{{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto animate-fade-in-up">
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('lecturer.quiz.index') }}" class="text-slate-500 hover:text-slate-800 font-bold text-sm transition-colors"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Bank Kuis</a>
                    <a href="{{ route('lecturer.quiz.edit', $quiz->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase tracking-widest px-4 py-2 rounded-xl shadow-[0_4px_0_0_#b45309] hover:translate-y-[2px] hover:shadow-none transition-all"><i class="fa-solid fa-pen mr-2"></i>Edit Kuis</a>
                </div>

                <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 shadow-sm mb-6">
                    <div class="inline-block bg-indigo-100 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest mb-3">Materi: {{ $quiz->material->title }}</div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight mb-2">{{ $quiz->title }}</h1>
                    <p class="text-slate-500 font-bold text-sm">Passing Grade: <span class="text-emerald-500">{{ $quiz->passing_grade }}</span> | Total Soal: <span class="text-slate-800">{{ $quiz->questions->count() }}</span></p>
                </div>

                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $q)
                    <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 shadow-sm">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="w-10 h-10 shrink-0 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black">{{ $index + 1 }}</div>
                            <h3 class="text-lg font-bold text-slate-800 pt-1">{{ $q->question_text }}</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-14">
                            @foreach(['a','b','c','d'] as $opt)
                            @php $isCorrect = $q->correct_option == $opt; @endphp
                            <div class="p-3 rounded-xl border-2 {{ $isCorrect ? 'border-emerald-500 bg-emerald-50 text-emerald-800 font-bold' : 'border-slate-100 bg-white text-slate-600 font-medium' }} flex items-center space-x-3">
                                <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black uppercase {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }}">{{ $opt }}</div>
                                <span class="text-sm">{{ $q->{'option_'.$opt} }}</span>
                                @if($isCorrect) <i class="fa-solid fa-check-circle ml-auto text-emerald-500 text-lg"></i> @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </main>
</div>
@endsection