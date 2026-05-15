@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: none; } }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ 
        activeTab: 'video', 
        showQuizModal: false, 
        isSubmittingProgress: false, 
        showWarning: false,
        currentIndex: 0,
        totalQuestions: {{ $quiz && $quiz->questions ? $quiz->questions->count() : 0 }},
        progress() {
            return this.totalQuestions > 0 ? ((this.currentIndex + 1) / this.totalQuestions) * 100 : 0;
        }
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
                        {{ $isLecturer ? 'Dosen Pengampu' : Auth::user()->getLevel() }}
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
                    <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create', 'lecturer.materials.edit', 'lecturer.quiz.create', 'lecturer.quiz.edit') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
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
                @else
                    <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-border-all w-6 text-center text-lg"></i><span>Beranda</span>
                    </a>
                    <a href="{{ route('student.material.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.material.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-book-open w-6 text-center text-lg"></i><span>Kurikulum</span>
                    </a>
                    <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-clipboard-list w-6 text-center text-lg"></i><span>Tugas Saya</span>
                    </a>
                    <a href="{{ route('student.progress.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.progress.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-chart-line w-6 text-center text-lg"></i><span>Laporan Progress</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t {{ $isLecturer ? 'border-slate-800 bg-slate-900' : 'border-slate-100 bg-white' }} shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-4 {{ $isLecturer ? 'text-slate-500 hover:bg-red-900/30 hover:text-red-500' : 'text-slate-500 hover:bg-red-50 hover:text-red-600' }} rounded-2xl font-bold text-sm w-full transition-all group">
                    <i class="fa-solid fa-power-off w-6 text-center text-lg group-hover:rotate-90 transition-transform"></i>
                    <span>Keluar </span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
        
        <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between shrink-0 z-10">
            <div class="flex items-center space-x-4">
                <a href="{{ route('student.material.index') }}" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#0b276b] hover:text-white transition-all hover:-translate-x-1 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-[#0b276b] uppercase tracking-widest mb-0.5">Misi Aktif</p>
                    <h1 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $material->title ?? 'Materi Belum Tersedia' }}</h1>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 bg-emerald-100 text-emerald-700 text-xs font-black uppercase tracking-widest rounded-xl border border-emerald-200 shadow-sm flex items-center space-x-2">
                    <i class="fa-solid fa-shield-cat"></i>
                    <span>Kasta: {{ $material->level ?? 'Menengah' }}</span>
                </span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-3/4">
                    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm overflow-hidden flex flex-col min-h-[600px] animate-fade-in-up">
                        
                        <div class="flex border-b-2 border-slate-100 bg-slate-50 overflow-x-auto shrink-0 p-2 space-x-2">
                            <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'bg-white border-2 border-[#0b276b] text-[#0b276b] shadow-sm' : 'border-2 border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-200'" class="flex-1 py-3 px-4 text-xs font-black uppercase tracking-widest rounded-xl transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-circle-play text-lg"></i>
                                <span>Video Materi</span>
                            </button>
                            <button @click="activeTab = 'teks'" :class="activeTab === 'teks' ? 'bg-white border-2 border-amber-500 text-amber-600 shadow-sm' : 'border-2 border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-200'" class="flex-1 py-3 px-4 text-xs font-black uppercase tracking-widest rounded-xl transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-book-open text-lg"></i>
                                <span>Modul Teks</span>
                            </button>
                            <button @click="activeTab = 'kode'" :class="activeTab === 'kode' ? 'bg-white border-2 border-emerald-500 text-emerald-600 shadow-sm' : 'border-2 border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-200'" class="flex-1 py-3 px-4 text-xs font-black uppercase tracking-widest rounded-xl transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-code text-lg"></i>
                                <span>Laboratorium Kode</span>
                            </button>
                        </div>

                        <div class="flex-1 bg-white p-8 relative">
                            
                            <div x-show="activeTab === 'video'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="h-full flex flex-col">
                                @if($material->video_url)
                                @php
                                    $embedUrl = $material->video_url;
                                    if (str_contains($embedUrl, 'watch?v=')) {
                                        $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                        $embedUrl = explode('&', $embedUrl)[0];
                                    } elseif (str_contains($embedUrl, 'youtu.be/')) {
                                        $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $embedUrl);
                                        $embedUrl = explode('?', $embedUrl)[0];
                                    }
                                @endphp
                                <div class="w-full aspect-video bg-slate-900 rounded-2xl overflow-hidden relative shadow-inner flex flex-col border-4 border-slate-800">
                                    <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                @else
                                <div class="w-full aspect-video bg-slate-50 border-4 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                        <i class="fa-solid fa-video-slash text-3xl"></i>
                                    </div>
                                    <span class="font-black text-sm uppercase tracking-widest text-slate-500">Video belum tersedia.</span>
                                </div>
                                @endif
                            </div>

                            <div x-show="activeTab === 'teks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                                @if(!empty($material->pdf_path))
                                <div>
                                    <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center">
                                        <i class="fa-solid fa-file-pdf text-red-500 mr-2 text-2xl"></i> Dokumen Modul Lengkap
                                    </h3>
                                    <div class="rounded-2xl overflow-hidden shadow-lg border-2 border-slate-200 bg-[#323639] w-full aspect-video relative">
                                        <iframe src="{{ asset('storage/' . str_replace('public/', '', $material->pdf_path)) }}#view=Fit" class="w-full h-full" frameborder="0"></iframe>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-20">
                                    <i class="fa-solid fa-file-circle-xmark text-6xl text-slate-200 mb-4"></i>
                                    <p class="text-slate-400 font-black uppercase tracking-widest">GM belum ngasih file Modul PDF.</p>
                                </div>
                                @endif
                            </div>

                            <div x-show="activeTab === 'kode'" 
                                 x-transition:enter="transition ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 translate-y-4" 
                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                 style="display: none;" 
                                 class="h-full flex flex-col"
                                 x-data="{ 
                                     rawCode: @js($material->code_visualization ?? 'Tidak ada kode visualisasi.'),
                                     displayedCode: '',
                                     isPlaying: false,
                                     isFinished: false,
                                     startSimulation() {
                                         if(this.isPlaying) return;
                                         this.isPlaying = true;
                                         this.isFinished = false;
                                         this.displayedCode = '';
                                         let i = 0;
                                         let speed = 25;
                                         let interval = setInterval(() => {
                                             this.displayedCode += this.rawCode.charAt(i);
                                             i++;
                                             let container = document.getElementById('code-terminal-body');
                                             if(container) container.scrollTop = container.scrollHeight;
                                             if(i >= this.rawCode.length) {
                                                 clearInterval(interval);
                                                 this.isPlaying = false;
                                                 this.isFinished = true;
                                             }
                                         }, speed);
                                     }
                                 }">
                                <div class="flex justify-between items-center mb-6 shrink-0">
                                    <div>
                                        <h3 class="font-black text-slate-800 text-lg uppercase tracking-tight">Simulasi Penulisan Kode</h3>
                                        <p class="text-xs font-bold text-slate-500">Lihat bagaimana kode sakti ini diracik baris demi baris.</p>
                                    </div>
                                    <button @click="startSimulation()" :disabled="isPlaying" class="bg-emerald-500 hover:bg-emerald-600 disabled:bg-emerald-300 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl flex items-center space-x-2 transition-all shadow-[0_4px_0_0_#059669] hover:shadow-[0_2px_0_0_#059669] hover:translate-y-[2px] active:scale-95">
                                        <i class="fa-solid" :class="isPlaying ? 'fa-spinner fa-spin' : (isFinished ? 'fa-rotate-right' : 'fa-play')"></i>
                                        <span x-text="isPlaying ? 'Hacker Mode...' : (isFinished ? 'Ulangi Simulasi' : 'Jalankan')"></span>
                                    </button>
                                </div>
                                <div class="bg-[#1e1e1e] rounded-2xl flex-1 flex flex-col overflow-hidden shadow-2xl border-2 border-slate-800">
                                    <div class="bg-[#2d2d2d] px-4 py-3 flex items-center justify-between border-b border-black/50 shrink-0">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                        </div>
                                        <span class="text-slate-400 text-[10px] font-mono select-none">player@algolearn: ~/quests</span>
                                    </div>
                                    <div id="code-terminal-body" class="p-6 flex-1 overflow-y-auto font-mono text-sm text-emerald-400 relative">
                                        <div x-show="!isPlaying && !isFinished" class="absolute inset-0 flex flex-col items-center justify-center text-slate-600 pointer-events-none bg-[#1e1e1e]">
                                            <i class="fa-solid fa-laptop-code text-6xl mb-4 opacity-50"></i>
                                            <p class="font-sans text-xs font-black uppercase tracking-widest">Klik "Jalankan" untuk memulai hacking.</p>
                                        </div>
                                        <pre class="leading-relaxed whitespace-pre-wrap"><span x-text="displayedCode"></span><span x-show="isPlaying" class="inline-block w-2.5 h-4 bg-emerald-400 animate-pulse ml-1 align-middle"></span></pre>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/4 space-y-6">
                    <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 shadow-sm text-center transition-all hover:border-indigo-300">
                        @if($quiz && $quiz->questions->count() > 0)
                            <h3 class="font-black text-slate-800 mb-2 uppercase tracking-tight">Evaluasi Tersedia</h3>
                            <p class="text-xs font-bold text-slate-500 mb-5 leading-relaxed">Uji pemahaman lu buat nyelesaiin materi ini secara resmi.</p>
                            <button @click="
                                    isSubmittingProgress = true;
                                    fetch('{{ route('student.material.complete', $material->id) }}', {
                                        method: 'POST', 
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                    }).then(() => { 
                                        isSubmittingProgress = false; 
                                        showQuizModal = true; 
                                    })
                                " 
                                :disabled="isSubmittingProgress"
                                class="w-full py-4 bg-indigo-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#4f46e5] hover:translate-y-[2px] hover:shadow-none transition-all disabled:opacity-50 text-xs">
                                <span x-show="!isSubmittingProgress"><i class="fa-solid fa-check-double mr-2"></i> Mulai Evaluasi</span>
                                <span x-show="isSubmittingProgress"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading...</span>
                            </button>
                        @else
                            <h3 class="font-black text-slate-800 mb-2 uppercase tracking-tight">Materi Selesai</h3>
                            <p class="text-xs font-bold text-slate-500 mb-5 leading-relaxed">Tandai kelar kalau lu udah ngerasa paham semua isinya.</p>
                            <button @click="
                                    isSubmittingProgress = true;
                                    fetch('{{ route('student.material.complete', $material->id) }}', {
                                        method: 'POST', 
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                    }).then(() => { window.location.href = '{{ route('student.tasks.index') }}' })
                                " 
                                :disabled="isSubmittingProgress"
                                class="w-full py-4 bg-emerald-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#059669] hover:translate-y-[2px] hover:shadow-none transition-all text-xs">
                                <span x-show="!isSubmittingProgress"><i class="fa-solid fa-check mr-2"></i> Tandai Selesai</span>
                                <span x-show="isSubmittingProgress"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading...</span>
                            </button>
                        @endif
                    </div>

                    @if(!empty($material->pdf_path))
                    <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 shadow-sm hover:border-red-300 transition-colors">
                        <h3 class="font-black text-slate-800 mb-3 flex items-center space-x-2 uppercase tracking-tight">
                            <i class="fa-solid fa-download text-red-500"></i>
                            <span>Modul Offline</span>
                        </h3>
                        <p class="text-xs font-bold text-slate-500 mb-6 leading-relaxed">Fakir kuota? Download aja PDF-nya biar bisa dibaca pas nongkrong kaga pake wifi.</p>
                        <a href="{{ asset('storage/' . str_replace('public/', '', $material->pdf_path)) }}" download class="block w-full border-2 border-slate-200 text-slate-500 hover:border-red-500 hover:bg-red-50 hover:text-red-700 text-center font-black uppercase tracking-widest py-3 rounded-xl transition-all text-xs">
                            Download PDF
                        </a>
                    </div>
                    @endif
                </div>

            </div>
        </div>

        @if($quiz && $quiz->questions->count() > 0)
        <div x-show="showQuizModal"
             style="display: none;"
             class="fixed inset-0 z-[9999] bg-white flex flex-col overflow-hidden"
             x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full">

            <header class="w-full px-6 py-8 flex items-center justify-between max-w-5xl mx-auto gap-6 shrink-0 z-50 bg-white border-b-2 border-slate-50">
                <button type="button" @click="showWarning = true" class="text-slate-300 hover:text-red-500 transition-colors active:scale-90" title="Tutup">
                    <i class="fa-solid fa-xmark text-3xl"></i>
                </button>
                <div class="flex-1 h-5 bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full transition-all duration-500 ease-out relative" :style="'width: ' + progress() + '%'">
                        <div class="absolute top-1 left-2 right-2 h-1.5 bg-white/30 rounded-full"></div>
                    </div>
                </div>
                <div class="font-black text-amber-500 text-lg w-12 text-center" x-text="(currentIndex + 1) + '/' + totalQuestions"></div>
            </header>

            <form action="{{ route('student.quiz.evaluasi.submit', $quiz->id) }}" method="POST" class="flex-1 flex flex-col overflow-hidden relative bg-slate-50">
                @csrf
                <div class="flex-1 overflow-y-auto px-4">
                    <div class="max-w-3xl mx-auto w-full py-8 pb-40 relative">
                        @foreach($quiz->questions as $index => $q)
                            @php
                                $parsedText = e($q->question_text);
                                $parsedText = nl2br($parsedText);
                                $parsedText = preg_replace_callback('/\[code\](.*?)\[\/code\]/is', function($matches) {
                                    $cleanCode = str_replace('<br />', '', $matches[1]);
                                    return '<div class="text-left"><pre class="bg-[#0f172a] text-amber-400 p-6 rounded-2xl my-6 overflow-x-auto font-mono text-sm shadow-inner border-2 border-slate-800"><code>' . trim($cleanCode) . '</code></pre></div>';
                                }, $parsedText);
                            @endphp

                            <div x-show="currentIndex === {{ $index }}" 
                                 style="display: none;"
                                 x-transition:enter="transition-all ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 translate-x-12" 
                                 x-transition:enter-end="opacity-100 translate-x-0" 
                                 class="w-full">
                                
                                <div class="bg-white p-8 sm:p-10 rounded-[2rem] border-2 border-slate-200 shadow-sm">
                                    <h2 class="font-black text-slate-800 mb-10 text-2xl md:text-3xl leading-relaxed">
                                        {!! $parsedText !!}
                                    </h2>

                                    @if($q->type == 'arrange')
                                        <div x-data="{
                                                available: @js(explode(',', $q->options)),
                                                selected: [],
                                                moveToSelected(i) {
                                                    this.selected.push(this.available[i]);
                                                    this.available.splice(i, 1);
                                                },
                                                moveToAvailable(i) {
                                                    this.available.push(this.selected[i]);
                                                    this.selected.splice(i, 1);
                                                }
                                             }">
                                             
                                            <div class="min-h-[120px] py-6 border-t-2 border-b-2 border-slate-100 mb-10 flex flex-wrap gap-3 items-center bg-slate-50 px-6 rounded-2xl">
                                                <template x-if="selected.length === 0">
                                                    <div class="w-full border-2 border-dashed border-slate-300 rounded-2xl h-14 flex items-center justify-center bg-white text-slate-400 font-bold text-sm">Susun balok di sini...</div>
                                                </template>
                                                <template x-for="(block, i) in selected" :key="i">
                                                    <button @click="moveToAvailable(i)" type="button" class="px-5 py-3 bg-amber-500 text-white font-mono text-base font-bold rounded-2xl shadow-[0_4px_0_0_#b45309] active:translate-y-[4px] active:shadow-none transition-all">
                                                        <span x-text="block"></span>
                                                    </button>
                                                </template>
                                            </div>
                                            
                                            <div class="flex flex-wrap gap-3 justify-center p-6 bg-slate-100 border-2 border-slate-200 rounded-2xl">
                                                <template x-for="(block, i) in available" :key="i">
                                                    <button @click="moveToSelected(i)" type="button" class="px-5 py-3 bg-white text-slate-700 font-mono text-base font-bold rounded-2xl border-2 border-slate-200 shadow-[0_4px_0_0_#cbd5e1] active:translate-y-[4px] active:shadow-none transition-all">
                                                        <span x-text="block"></span>
                                                    </button>
                                                </template>
                                            </div>
                                            <input type="hidden" name="answers[{{ $q->id }}]" :value="selected.join(' ')">
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach(['a','b','c','d'] as $opt)
                                                <label class="cursor-pointer group block">
                                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="hidden peer">
                                                    <div class="p-6 rounded-2xl border-2 border-slate-200 peer-checked:border-amber-400 peer-checked:bg-amber-50 text-slate-600 peer-checked:text-amber-900 font-bold transition-all shadow-[0_4px_0_0_#e2e8f0] peer-checked:shadow-[0_4px_0_0_#fbbf24] active:translate-y-[4px] active:shadow-none flex items-center bg-white">
                                                        <span class="inline-flex w-10 h-10 border-2 border-slate-200 text-slate-400 peer-checked:border-amber-400 peer-checked:text-amber-500 peer-checked:bg-white rounded-xl items-center justify-center text-sm font-black mr-4 uppercase shrink-0 transition-colors">{{ $opt }}</span>
                                                        <span class="text-lg">{{ $q->{'option_'.$opt} }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="fixed bottom-0 left-0 w-full bg-white border-t-2 border-slate-200 px-6 py-6 sm:py-8 z-50 shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
                    <div class="max-w-4xl mx-auto flex justify-between items-center">
                        <button type="button" x-show="currentIndex > 0" @click="currentIndex--" class="px-6 sm:px-8 py-4 rounded-2xl font-black text-slate-400 uppercase tracking-widest hover:bg-slate-100 active:scale-95 transition-all text-xs sm:text-sm" style="display: none;">
                            Kembali
                        </button>
                        <div x-show="currentIndex === 0" class="w-16 sm:w-24"></div>

                        <button type="button" x-show="currentIndex < totalQuestions - 1" @click="currentIndex++" class="px-10 sm:px-12 py-4 bg-amber-500 text-white font-black text-lg sm:text-xl uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#b45309] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#b45309] active:translate-y-[6px] active:shadow-none transition-all w-full md:w-auto text-center">
                            Lanjut
                        </button>

                        <button type="submit" x-show="currentIndex === totalQuestions - 1" style="display: none;" class="px-10 sm:px-12 py-4 bg-indigo-600 text-white font-black text-lg sm:text-xl uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#4338ca] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#4338ca] active:translate-y-[6px] active:shadow-none transition-all w-full md:w-auto text-center flex items-center justify-center gap-3">
                            <i class="fa-solid fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div x-show="showWarning" class="fixed inset-0 z-[10000] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 relative shadow-2xl border-4 border-red-500 text-center" @click.away="showWarning = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <div class="w-24 h-24 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg rotate-12">
                    <i class="fa-solid fa-triangle-exclamation text-5xl -rotate-12"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Eits, Mau Kemana?</h3>
                <p class="text-slate-500 font-bold mb-8 leading-relaxed">Selesaikan evaluasi ini dulu lek! Buktikan kalau lu beneran udah paham materinya sebelum kabur.</p>
                <button @click="showWarning = false" class="w-full py-4 bg-red-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#b91c1c] hover:translate-y-[2px] hover:shadow-none transition-all">
                    Oke, Gue Kerjain!
                </button>
            </div>
        </div>
        @endif
        
    </main>
</div>
@endsection