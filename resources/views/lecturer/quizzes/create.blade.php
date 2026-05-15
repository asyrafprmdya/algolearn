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

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100" x-data="{
        questions: [{ id: 1, text: '', type: '{{ $category == "evaluation" ? "multiple_choice" : "arrange" }}', a: '', b: '', c: '', d: '', answer: 'a', options_arrange: '', correct_option_arrange: '' }],
        activeModalId: null,
        addQuestion() { this.questions.push({ id: Date.now(), text: '', type: '{{ $category == "evaluation" ? "multiple_choice" : "arrange" }}', a: '', b: '', c: '', d: '', answer: 'a', options_arrange: '', correct_option_arrange: '' }); },
        removeQuestion(id) { this.questions = this.questions.filter(q => q.id !== id); }
    }">
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">GM {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative pb-32">
            <div class="max-w-4xl mx-auto animate-fade-in-up">
                
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <a href="{{ route('lecturer.quiz.index') }}" class="text-slate-400 hover:text-amber-500 transition-colors">
                            <i class="fa-solid fa-circle-left text-2xl"></i>
                        </a>
                        <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">
                            Buat {{ $category == 'evaluation' ? 'Evaluasi' : 'Arena Latihan' }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Misi: {{ $material->title }}</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Siksa mahasiswamu dengan pertanyaan-pertanyaan menjebak. Ketik [code]...[/code] untuk menonjolkan kodingan.</p>
                </div>

                <form action="{{ route('lecturer.quiz.store', $material->id) }}" method="POST">
                    @csrf
                    
                    <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm mb-8 relative group hover:border-amber-300 transition-colors z-20">
                        
                        <div class="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none">
                            <div class="absolute -right-6 -top-6 text-amber-50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-gear text-[10rem]"></i>
                            </div>
                        </div>

                        <div class="relative z-10 p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2 tracking-widest ml-1">Judul Ujian / Kuis</label>
                                <input type="text" name="title" required class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-50 text-slate-800 font-bold transition-all" placeholder="Contoh: Evaluasi Mematikan">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2 tracking-widest ml-1">Nilai Kelulusan (KKM)</label>
                                <div class="relative">
                                    <input type="number" name="passing_grade" value="70" required class="w-full px-5 py-4 pl-14 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 text-slate-800 font-black text-lg transition-all">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-lg">
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-span-1 md:col-span-2 mt-2 flex flex-col md:flex-row items-start md:items-center justify-between p-6 rounded-2xl border-2 border-indigo-200 bg-indigo-50/50">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner text-2xl">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-indigo-400 uppercase mb-0.5 tracking-widest">Kategori Paket (Digembok)</label>
                                        <p class="text-lg font-black text-indigo-900 uppercase tracking-widest">
                                            @if($category == 'evaluation')
                                                <i class="fa-solid fa-star text-amber-500 mr-2"></i> Evaluasi Materi (Pop-up)
                                            @else
                                                <i class="fa-solid fa-gamepad text-emerald-500 mr-2"></i> Arena Latihan (Tugas)
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0 px-5 py-3 bg-white rounded-xl border-2 border-indigo-100 shadow-sm">
                                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Aman dari Jari Kepeleset</p>
                                </div>
                                <input type="hidden" name="category" value="{{ $category }}">
                            </div>

                        </div>
                    </div>

                    <div class="space-y-8 mb-10">
                        <template x-for="(q, index) in questions" :key="q.id">
                            <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm p-8 relative transition-all"
                                 x-data="{ openType: false }"
                                 :class="openType ? 'z-50 ring-4 ring-indigo-50' : 'z-10'"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                                
                                <div class="flex justify-between items-center mb-8 pb-6 border-b-2 border-slate-100">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-800 text-white flex items-center justify-center font-black shadow-lg text-xl">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div>
                                            <h3 class="font-black text-slate-800 text-xl uppercase tracking-tight">Pertanyaan</h3>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Konfigurasi Soal</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeQuestion(q.id)" x-show="questions.length > 1" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center space-x-2 active:scale-95">
                                        <i class="fa-solid fa-trash"></i> <span>Hapus</span>
                                    </button>
                                </div>

                                <div class="mb-8 relative" @click.outside="openType = false">
                                    <label class="block text-xs font-black text-slate-500 uppercase mb-3 tracking-widest ml-1">Tipe Evaluasi</label>
                                    <button type="button" @click="openType = !openType" class="w-full flex items-center justify-between px-6 py-5 rounded-2xl border-2 border-slate-200 bg-slate-50 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 text-slate-800 font-black text-sm uppercase tracking-widest transition-all cursor-pointer">
                                        <span x-text="q.type === 'multiple_choice' ? 'Pilihan Ganda (Klasik)' : 'Susun Kode (Gamifikasi)'"></span>
                                        <i class="fa-solid fa-caret-down text-xl transition-transform duration-300" :class="openType ? 'rotate-180 text-indigo-500' : 'text-slate-400'"></i>
                                    </button>
                                    
                                    <div x-show="openType" x-transition.opacity.duration.200ms class="absolute w-full mt-2 bg-white border-2 border-slate-100 rounded-2xl shadow-xl overflow-hidden" style="display: none;">
                                        <button type="button" @click="q.type = 'multiple_choice'; openType = false" class="w-full text-left px-6 py-4 hover:bg-indigo-50 font-black text-sm uppercase tracking-widest transition-colors flex items-center justify-between" :class="q.type === 'multiple_choice' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600'">
                                            <span>Pilihan Ganda (Klasik)</span>
                                            <i class="fa-solid fa-check text-indigo-500" x-show="q.type === 'multiple_choice'"></i>
                                        </button>
                                        <button type="button" @click="q.type = 'arrange'; openType = false" class="w-full text-left px-6 py-4 border-t-2 border-slate-50 hover:bg-indigo-50 font-black text-sm uppercase tracking-widest transition-colors flex items-center justify-between" :class="q.type === 'arrange' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600'">
                                            <span>Susun Kode (Gamifikasi)</span>
                                            <i class="fa-solid fa-check text-indigo-500" x-show="q.type === 'arrange'"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" x-bind:name="'questions['+index+'][type]'" x-model="q.type">
                                </div>

                                <div class="mb-8">
                                    <label class="block text-xs font-black text-slate-500 uppercase mb-3 tracking-widest ml-1">Kalimat Pertanyaan</label>
                                    <textarea x-bind:name="'questions['+index+'][question_text]'" x-model="q.text" required rows="3" class="w-full px-6 py-5 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 text-slate-700 font-bold text-lg leading-relaxed transition-all resize-none shadow-inner bg-slate-50" placeholder="Ketik [code]...[/code] jika ingin menampilkan box kodingan..."></textarea>
                                </div>
                                
                                <div x-show="q.type === 'multiple_choice'" x-transition>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-black uppercase">A</span>
                                            <input type="text" x-bind:name="'questions['+index+'][option_a]'" x-model="q.a" class="w-full pl-16 pr-5 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all bg-slate-50">
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-black uppercase">B</span>
                                            <input type="text" x-bind:name="'questions['+index+'][option_b]'" x-model="q.b" class="w-full pl-16 pr-5 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all bg-slate-50">
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-black uppercase">C</span>
                                            <input type="text" x-bind:name="'questions['+index+'][option_c]'" x-model="q.c" class="w-full pl-16 pr-5 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all bg-slate-50">
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-black uppercase">D</span>
                                            <input type="text" x-bind:name="'questions['+index+'][option_d]'" x-model="q.d" class="w-full pl-16 pr-5 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all bg-slate-50">
                                        </div>
                                    </div>
                                    
                                    <div class="bg-emerald-50 rounded-2xl p-6 border-2 border-emerald-200 flex flex-col md:flex-row md:items-center justify-between relative overflow-hidden group transition-all gap-4">
                                        <div class="flex items-center space-x-4 relative z-10">
                                            <div class="w-12 h-12 bg-emerald-200 text-emerald-600 rounded-full flex items-center justify-center text-2xl shadow-inner">
                                                <i class="fa-solid fa-key"></i>
                                            </div>
                                            <div>
                                                <label class="text-sm font-black text-emerald-900 uppercase tracking-widest">Kunci Jawaban Ganda</label>
                                                <p class="text-xs text-emerald-700 font-bold">Pilih abjad yang paling benar.</p>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" x-bind:name="'questions['+index+'][correct_option_mc]'" x-model="q.answer">

                                        <button type="button" @click="activeModalId = q.id" 
                                                class="px-8 py-4 rounded-xl border-2 border-emerald-300 bg-white flex items-center justify-center space-x-3 cursor-pointer hover:border-emerald-500 hover:shadow-lg transition-all active:scale-95 w-full md:w-auto">
                                            <span class="uppercase w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-black text-lg" x-text="q.answer"></span>
                                            <span class="font-black text-emerald-800 text-sm uppercase tracking-widest">Ganti Kunci</span>
                                        </button>
                                    </div>
                                </div>

                                <div x-show="q.type === 'arrange'" x-transition style="display: none;" class="mt-6">
                                    <div class="p-8 rounded-3xl border-4 border-dashed border-amber-300 bg-amber-50/50 relative">
                                        <div class="absolute -top-4 left-8 bg-amber-400 text-amber-900 px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2 shadow-sm">
                                            <i class="fa-solid fa-shapes"></i> Editor Gamifikasi
                                        </div>
                                        
                                        <div class="mb-8 mt-2">
                                            <label class="flex items-center space-x-2 text-xs font-black text-amber-800 uppercase mb-3 tracking-widest ml-1">
                                                <i class="fa-solid fa-cubes text-amber-500 text-lg"></i>
                                                <span>Pecahan Kode Balok (Pisahkan dengan Koma)</span>
                                            </label>
                                            <input type="text" x-bind:name="'questions['+index+'][options_arrange]'" x-model="q.options_arrange" placeholder="int,main(),{,cout <<,return 0;,}" class="w-full px-6 py-5 rounded-2xl border-2 border-amber-200 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 font-mono text-sm text-slate-800 font-bold transition-all shadow-inner bg-white">
                                            
                                            <div class="mt-4 p-4 rounded-2xl bg-white border-2 border-slate-100">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Live Preview Balok Mahasiswa:</p>
                                                <div class="flex flex-wrap gap-2">
                                                    <template x-if="!q.options_arrange">
                                                        <span class="text-xs font-bold text-slate-300 italic">Ketik sesuatu di atas untuk melihat preview...</span>
                                                    </template>
                                                    <template x-for="block in q.options_arrange.split(',').filter(i => i.trim() !== '')">
                                                        <span class="px-4 py-2 bg-amber-500 text-white font-mono text-xs rounded-xl shadow-[0_4px_0_0_#b45309]" x-text="block.trim()"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="flex items-center space-x-2 text-xs font-black text-emerald-800 uppercase mb-3 tracking-widest ml-1">
                                                <i class="fa-solid fa-check-double text-emerald-500 text-lg"></i>
                                                <span>Susunan Jawaban Valid (Pisahkan dengan Spasi)</span>
                                            </label>
                                            <input type="text" x-bind:name="'questions['+index+'][correct_option_arrange]'" x-model="q.correct_option_arrange" placeholder="int main() { cout << return 0; }" class="w-full px-6 py-5 rounded-2xl border-2 border-emerald-300 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 font-mono text-sm text-emerald-900 font-black transition-all shadow-inner bg-emerald-50">
                                            <p class="text-xs text-emerald-600 mt-3 font-bold ml-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Penting: Pastikan spasi dan teks sama persis dengan urutan balok yang benar.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 mb-12">
                        <button type="button" @click="addQuestion()" class="flex-1 py-5 bg-slate-50 border-2 border-dashed border-amber-300 text-amber-600 font-black uppercase tracking-widest rounded-3xl hover:bg-amber-50 hover:border-amber-500 transition-all flex items-center justify-center space-x-3 active:scale-95 text-sm">
                            <i class="fa-solid fa-plus-circle text-xl"></i> 
                            <span>Tambah Soal Kuis</span>
                        </button>
                        
                        <button type="submit" class="flex-1 py-5 bg-indigo-600 text-white font-black uppercase tracking-widest rounded-3xl shadow-[0_6px_0_0_#4f46e5] hover:shadow-[0_2px_0_0_#4f46e5] hover:translate-y-[4px] transition-all flex items-center justify-center space-x-3 text-sm">
                            <i class="fa-solid fa-rocket text-xl"></i> 
                            <span>Simpan & Rilis Kuis</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="activeModalId !== null" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-lg"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            
            <div class="absolute inset-0 w-full h-full" @click="activeModalId = null"></div>

            <div class="bg-white rounded-[3rem] w-full max-w-xl p-12 relative z-[1000] shadow-2xl border-4 border-indigo-500"
                 @click.stop
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-10">
                
                <div class="text-center mb-10">
                    <div class="w-24 h-24 bg-indigo-100 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl rotate-12">
                        <i class="fa-solid fa-check-double text-5xl -rotate-12"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Kunci Jawaban</h3>
                    <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-xs">Pilih abjad yang benar untuk soal <span x-text="activeModalId !== null ? questions.findIndex(q => q.id === activeModalId) + 1 : ''" class="text-indigo-600"></span></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <template x-for="opt in ['a','b','c','d']" :key="opt">
                        <button type="button" @click="if(activeModalId !== null) { let idx = questions.findIndex(q => q.id === activeModalId); questions[idx].answer = opt; activeModalId = null; }"
                                class="p-6 rounded-3xl border-4 transition-all hover:-translate-y-2 active:scale-95 flex flex-col items-center justify-center gap-3 group"
                                :class="activeModalId !== null && questions.find(q => q.id === activeModalId)?.answer === opt ? 'bg-indigo-600 border-indigo-700 text-white shadow-[0_8px_0_0_#3730a3]' : 'bg-slate-50 border-slate-200 text-slate-700 hover:border-indigo-300 hover:bg-indigo-50 shadow-[0_8px_0_0_#e2e8f0]'">
                            <span class="uppercase w-16 h-16 rounded-2xl flex items-center justify-center font-black text-4xl shadow-inner transition-colors"
                                  :class="activeModalId !== null && questions.find(q => q.id === activeModalId)?.answer === opt ? 'bg-indigo-500 text-white' : 'bg-white text-slate-400 group-hover:text-indigo-600'"
                                  x-text="opt"></span>
                        </button>
                    </template>
                </div>

                <button type="button" @click="activeModalId = null" class="w-full mt-10 py-5 bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-600 font-black text-sm uppercase tracking-widest rounded-2xl transition-colors flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Tutup</span>
                </button>
            </div>
        </div>

    </main>
</div>
@endsection