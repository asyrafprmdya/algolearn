@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: none; } }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ activeTab: 'video', showQuizModal: false, isSubmittingProgress: false }">
    
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
                @else
                    <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-border-all w-6 text-center text-lg"></i><span>Beranda</span>
                    </a>
                    <a href="{{ route('student.material.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.material.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-book-open w-6 text-center text-lg"></i><span>Materi</span>
                    </a>
                    <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                        <i class="fa-solid fa-clipboard-list w-6 text-center text-lg"></i><span>Latihan</span>
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
        <div x-show="showQuizModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-md" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col relative shadow-2xl border-4 border-indigo-500" @click.away="alert('Selesaikan evaluasi terlebih dahulu!')">
                
                <div class="px-8 py-6 border-b-2 border-slate-100 bg-indigo-50 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-2xl font-black text-indigo-900 uppercase tracking-tight">Evaluasi Kilat</h3>
                        <p class="text-xs font-bold text-indigo-600 mt-1">Selesaikan ini buat buktiin lu beneran baca!</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-xl font-black shadow-lg">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                </div>

                <div class="p-8 overflow-y-auto flex-1 bg-slate-50 custom-scrollbar">
                    <form action="{{ route('student.quiz.evaluasi.submit', $quiz->id) }}" method="POST" id="evalForm">
                        @csrf
                        <div class="space-y-6">
                            @foreach($quiz->questions as $index => $q)
                                @php
                                    $parsedText = e($q->question_text);
                                    $parsedText = nl2br($parsedText);
                                    $parsedText = preg_replace_callback('/\[code\](.*?)\[\/code\]/is', function($matches) {
                                        $cleanCode = str_replace('<br />', '', $matches[1]);
                                        return '<pre class="bg-[#0f172a] text-emerald-400 p-5 rounded-2xl my-4 overflow-x-auto font-mono text-sm shadow-inner border-2 border-slate-800"><code>' . trim($cleanCode) . '</code></pre>';
                                    }, $parsedText);
                                @endphp

                                @if($q->type == 'arrange')
                                    <div class="bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-sm hover:border-indigo-200 transition-colors"
                                         x-data="{
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
                                        <h4 class="font-bold text-slate-800 mb-5 leading-relaxed">
                                            <span class="text-indigo-500 mr-2">{{ $index + 1 }}.</span> {!! $parsedText !!}
                                        </h4>
                                        <div class="min-h-[80px] p-4 rounded-xl border-2 border-dashed border-indigo-300 bg-indigo-50/50 mb-6 flex flex-wrap gap-2 items-center">
                                            <template x-if="selected.length === 0">
                                                <span class="text-slate-400 text-xs font-bold w-full text-center">Tap blok di bawah buat nyusun...</span>
                                            </template>
                                            <template x-for="(block, i) in selected" :key="i">
                                                <button @click="moveToAvailable(i)" type="button" class="px-4 py-2 bg-indigo-600 text-white font-mono text-xs rounded-lg shadow-[0_3px_0_0_#4338ca] active:translate-y-[3px] active:shadow-none transition-all">
                                                    <span x-text="block"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <div class="p-4 rounded-xl bg-slate-100 flex flex-wrap gap-2 justify-center">
                                            <template x-for="(block, i) in available" :key="i">
                                                <button @click="moveToSelected(i)" type="button" class="px-4 py-2 bg-white text-slate-700 font-mono text-xs rounded-lg border-2 border-slate-200 shadow-[0_3px_0_0_#cbd5e1] active:translate-y-[3px] active:shadow-none transition-all">
                                                    <span x-text="block"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <input type="hidden" name="answers[{{ $q->id }}]" :value="selected.join(' ')">
                                    </div>
                                @else
                                    <div class="bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-sm hover:border-indigo-200 transition-colors">
                                        <h4 class="font-bold text-slate-800 mb-5 leading-relaxed"><span class="text-indigo-500 mr-2">{{ $index + 1 }}.</span> {!! $parsedText !!}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach(['a','b','c','d'] as $opt)
                                            <label class="cursor-pointer group">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="hidden peer" required>
                                                <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 text-slate-600 peer-checked:text-indigo-800 font-medium transition-all flex items-center">
                                                    <span class="inline-flex w-8 h-8 bg-slate-100 text-slate-500 peer-checked:bg-indigo-500 peer-checked:text-white rounded-lg items-center justify-center text-xs font-black mr-3 uppercase shrink-0 transition-colors">{{ $opt }}</span>
                                                    <span class="text-sm">{{ $q->{'option_'.$opt} }}</span>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="px-8 py-6 border-t-2 border-slate-100 bg-white shrink-0">
                    <button type="button" onclick="document.getElementById('evalForm').submit()" class="w-full py-4 bg-indigo-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#4f46e5] hover:translate-y-[2px] hover:shadow-none transition-all">
                        Kumpulkan Evaluasi
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </main>
</div>
@endsection