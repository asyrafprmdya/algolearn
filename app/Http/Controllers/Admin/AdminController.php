@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: none; }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
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
                        <i class="fa-solid fa-satellite-dish w-6 text-center text-lg"></i><span>God's Eye</span>
                    </a>
                    <a href="{{ route('lecturer.materials.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-boxes-stacked w-6 text-center text-lg"></i><span>Inventori Materi</span>
                    </a>
                    <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create', 'lecturer.materials.edit', 'lecturer.quiz.create') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-hammer w-6 text-center text-lg"></i><span>Pabrik Quest</span>
                    </a>
                    <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.students.progress') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-crosshairs w-6 text-center text-lg"></i><span>CCTV Player</span>
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
        questions: [{ text: '', a: '', b: '', c: '', d: '', answer: 'a' }],
        activeModalIndex: null,
        addQuestion() { this.questions.push({ text: '', a: '', b: '', c: '', d: '', answer: 'a' }); },
        removeQuestion(index) { this.questions.splice(index, 1); }
    }">
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">GM {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative">
            <div class="max-w-4xl mx-auto animate-fade-in-up">
                
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <a href="{{ route('lecturer.materials.index') }}" class="text-slate-400 hover:text-amber-500 transition-colors">
                            <i class="fa-solid fa-circle-left text-2xl"></i>
                        </a>
                        <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">Arena Ujian</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Buat Kuis: {{ $material->title }}</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Siksa mahasiswamu dengan pertanyaan-pertanyaan menjebak.</p>
                </div>

                <form action="{{ route('lecturer.quiz.store', $material->id) }}" method="POST">
                    @csrf
                    
                    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-8 mb-8 relative overflow-hidden group hover:border-amber-300 transition-colors">
                        <div class="absolute -right-6 -top-6 text-amber-50 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-gear text-[10rem]"></i>
                        </div>
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2 tracking-widest ml-1">Judul Ujian / Kuis</label>
                                <input type="text" name="title" required class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-50 text-slate-800 font-bold transition-all" placeholder="Contoh: Evaluasi Array Mematikan">
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
                        </div>
                    </div>

                    <div class="space-y-6 mb-8">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-8"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                                
                                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-slate-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-800 text-white flex items-center justify-center font-black shadow-md">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <h3 class="font-black text-slate-800 text-lg uppercase tracking-tight">Pertanyaan</h3>
                                    </div>
                                    <button type="button" @click="removeQuestion(index)" x-show="questions.length > 1" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-colors flex items-center space-x-2">
                                        <i class="fa-solid fa-trash"></i> <span>Hapus</span>
                                    </button>
                                </div>

                                <textarea x-bind:name="'questions['+index+'][question_text]'" x-model="q.text" required rows="3" class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 mb-6 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-50 text-slate-700 font-bold leading-relaxed transition-all resize-none" placeholder="Tuliskan pertanyaan..."></textarea>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-black uppercase">A</span>
                                        <input type="text" x-bind:name="'questions['+index+'][option_a]'" x-model="q.a" required class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all">
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-black uppercase">B</span>
                                        <input type="text" x-bind:name="'questions['+index+'][option_b]'" x-model="q.b" required class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all">
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-black uppercase">C</span>
                                        <input type="text" x-bind:name="'questions['+index+'][option_c]'" x-model="q.c" required class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all">
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-black uppercase">D</span>
                                        <input type="text" x-bind:name="'questions['+index+'][option_d]'" x-model="q.d" required class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>
                                
                                <div class="bg-emerald-50 rounded-2xl p-6 border-2 border-emerald-100 flex items-center justify-between relative overflow-hidden group hover:border-emerald-300 transition-all">
                                    <div class="absolute -right-4 -bottom-4 text-emerald-100 group-hover:scale-110 group-hover:-rotate-12 transition-all duration-500">
                                        <i class="fa-solid fa-shield-check text-8xl"></i>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4 relative z-10">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-3xl shadow-lg shadow-emerald-100 rounded-full"></i>
                                        <div>
                                            <label class="text-sm font-black text-emerald-900 uppercase tracking-widest">Kunci Jawaban Sah</label>
                                            <p class="text-xs text-emerald-700 font-bold">Klik tombol untuk mengubah kunci.</p>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" x-bind:name="'questions['+index+'][correct_option]'" x-model="q.answer">

                                    <button type="button" @click="activeModalIndex = index" 
                                            class="px-8 py-3 rounded-xl border-2 border-emerald-200 bg-white flex items-center space-x-3 cursor-pointer group hover:border-emerald-500 hover:shadow-lg transition-all active:scale-95 relative z-10">
                                        <span class="uppercase w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-black text-xl group-hover:animate-pulse" x-text="q.answer"></span>
                                        <span class="font-black text-emerald-700">Ubah Kunci</span>
                                        <i class="fa-solid fa-chevron-right text-emerald-400 group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 mb-12">
                        <button type="button" @click="addQuestion()" class="flex-1 py-4 bg-slate-50 border-2 border-dashed border-amber-300 text-amber-600 font-black uppercase tracking-widest rounded-2xl hover:bg-amber-50 hover:border-amber-500 transition-all flex items-center justify-center space-x-3 active:scale-95">
                            <i class="fa-solid fa-plus-circle text-lg"></i> 
                            <span>Tambah Soal Baru</span>
                        </button>
                        
                        <button type="submit" class="flex-1 py-4 bg-emerald-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#059669] hover:shadow-[0_2px_0_0_#059669] hover:translate-y-[4px] hover:bg-emerald-600 transition-all flex items-center justify-center space-x-3">
                            <i class="fa-solid fa-cloud-arrow-up text-lg"></i> 
                            <span>Rilis Kuis</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="activeModalIndex !== null" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-lg"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            
            <div class="absolute inset-0 w-full h-full" @click="activeModalIndex = null"></div>

            <div class="bg-white rounded-[2.5rem] w-full max-w-xl p-12 relative z-[1000] shadow-2xl border-2 border-slate-100"
                 @click.stop
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-10">
                
                <div class="text-center mb-10">
                    <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl border-8 border-white">
                        <i class="fa-solid fa-check-double text-4xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Kunci Jawaban</h3>
                    <p class="text-slate-500 font-medium">Nomor Soal: <span x-text="activeModalIndex !== null ? activeModalIndex + 1 : ''" class="font-black text-slate-800"></span></p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <template x-for="opt in ['a','b','c','d']" :key="opt">
                        <button type="button" @click="if(activeModalIndex !== null) { questions[activeModalIndex].answer = opt; activeModalIndex = null; }"
                                class="p-6 rounded-2xl border-4 transition-all hover:-translate-y-2 active:scale-95 flex items-center space-x-5 group"
                                :class="activeModalIndex !== null && questions[activeModalIndex].answer === opt ? 'bg-emerald-600 border-emerald-700 text-white shadow-2xl' : 'bg-slate-50 border-slate-100 text-slate-700 hover:border-emerald-300 hover:bg-emerald-50'">
                            <span class="uppercase w-16 h-16 rounded-xl flex items-center justify-center font-black text-4xl shadow-md transition-colors"
                                  :class="activeModalIndex !== null && questions[activeModalIndex].answer === opt ? 'bg-white/20 text-white' : 'bg-white text-slate-500 group-hover:bg-white group-hover:text-emerald-700'"
                                  x-text="opt"></span>
                            <div class="text-left">
                                <span class="font-black text-lg uppercase tracking-wider block" :class="activeModalIndex !== null && questions[activeModalIndex].answer === opt ? 'text-white' : 'text-slate-800'">Opsi <span x-text="opt.toUpperCase()"></span></span>
                                <span class="text-[10px] font-bold block uppercase" :class="activeModalIndex !== null && questions[activeModalIndex].answer === opt ? 'text-emerald-100' : 'text-slate-400'">Klik Pilih</span>
                            </div>
                        </button>
                    </template>
                </div>

                <button type="button" @click="activeModalIndex = null" class="w-full mt-10 py-4 text-slate-400 hover:text-red-500 font-black text-xs uppercase tracking-widest transition-colors flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Batalkan</span>
                </button>
            </div>
        </div>

    </main>
</div>
@endsection