@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: none; } }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" 
    x-data="{
        questions: {{ Js::from($quiz->questions->map(fn($q) => ['question_text' => $q->question_text, 'option_a' => $q->option_a, 'option_b' => $q->option_b, 'option_c' => $q->option_c, 'option_d' => $q->option_d, 'correct_option' => $q->correct_option])) }}
    }">
    
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
            <div><h1 class="text-xl font-black text-slate-800 uppercase tracking-wide">Edit Kuis</h1></div>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">{{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto animate-fade-in-up">
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 flex items-start space-x-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
                        <ul class="list-disc list-inside text-sm font-bold">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('lecturer.quiz.update', $quiz->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 shadow-sm mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="md:col-span-3">
                                <label class="block text-xs font-black uppercase text-slate-500 mb-2">Judul Kuis</label>
                                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 font-bold text-slate-800 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-2">Passing Grade</label>
                                <input type="number" name="passing_grade" value="{{ old('passing_grade', $quiz->passing_grade) }}" required min="0" max="100" class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 font-black text-emerald-600 transition-all text-center">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 shadow-sm relative group">
                                <button type="button" @click="questions.splice(index, 1)" class="absolute -right-3 -top-3 w-10 h-10 bg-red-100 text-red-500 rounded-xl flex items-center justify-center border-4 border-white shadow-sm hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                
                                <div class="mb-6">
                                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Soal No. <span x-text="index + 1"></span></label>
                                    <textarea :name="'questions['+index+'][question_text]'" x-model="q.question_text" required rows="3" class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 font-bold text-slate-700 transition-all"></textarea>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <template x-for="opt in ['a','b','c','d']" :key="opt">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 shrink-0 bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center font-black uppercase" x-text="opt"></div>
                                            <input type="text" :name="'questions['+index+'][option_'+opt+']'" x-model="q['option_'+opt]" required class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 outline-none focus:border-amber-500 text-sm font-medium transition-all">
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <label class="block text-xs font-black uppercase text-slate-500 mb-3 text-center">Kunci Jawaban</label>
                                    <div class="flex justify-center space-x-4">
                                        <template x-for="opt in ['a','b','c','d']" :key="opt">
                                            <label class="cursor-pointer">
                                                <input type="radio" :name="'questions['+index+'][correct_option]'" :value="opt" x-model="q.correct_option" class="hidden peer" required>
                                                <div class="w-12 h-12 rounded-xl border-2 border-slate-100 flex items-center justify-center font-black uppercase text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 shadow-sm transition-all" x-text="opt"></div>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row gap-4">
                        <button type="button" @click="questions.push({question_text: '', option_a: '', option_b: '', option_c: '', option_d: '', correct_option: 'a'})" class="flex-1 py-4 bg-emerald-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#059669] hover:translate-y-[2px] hover:shadow-none transition-all">
                            <i class="fa-solid fa-plus-circle mr-2"></i> Tambah Soal
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-slate-900 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#0f172a] hover:translate-y-[2px] hover:shadow-none transition-all">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Kuis
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>
@endsection