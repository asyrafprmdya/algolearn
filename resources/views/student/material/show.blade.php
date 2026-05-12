@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ activeTab: 'video' }">
    
    @php
        // Otak bunglon: ngecek yang login ini Dosen atau Mahasiswa
        $isLecturer = Auth::user()->role === 'lecturer'; 
        
        // Ganti kulit otomatis
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
                    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
                        
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
                                <a href="{{ $material->video_url }}" target="_blank" class="w-full aspect-video bg-slate-900 rounded-2xl overflow-hidden relative shadow-inner flex flex-col items-center justify-center group cursor-pointer hover:bg-slate-800 transition-colors border-4 border-slate-800">
                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors"></div>
                                    <i class="fa-brands fa-youtube text-red-600 text-[6rem] group-hover:scale-110 group-hover:text-red-500 transition-all mb-4 drop-shadow-2xl relative z-10"></i>
                                    <span class="text-white font-black tracking-widest uppercase text-sm relative z-10 bg-black/50 px-6 py-2 rounded-full border border-slate-700">Klik untuk Menonton di YouTube</span>
                                </a>
                                @else
                                <div class="w-full aspect-video bg-slate-50 border-4 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                        <i class="fa-solid fa-video-slash text-3xl"></i>
                                    </div>
                                    <span class="font-black text-sm uppercase tracking-widest text-slate-500">GM belum ngasih link video.</span>
                                </div>
                                @endif
                                <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl">
                                    <h2 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-2 flex items-center space-x-2">
                                        <i class="fa-solid fa-lightbulb text-blue-500"></i> <span>Tips Dosen</span>
                                    </h2>
                                    <p class="text-blue-700 text-sm font-bold leading-relaxed">Tonton video ini dulu biar otak lu ada gambaran. Setelah paham logikanya, baru hajar teorinya di tab Modul Teks.</p>
                                </div>
                            </div>

                            <div x-show="activeTab === 'teks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                                @php
                                    $rawContent = $material->content ?? '';
                                    $safeContent = strip_tags($rawContent, '<b><i><u><strong><em><br><p><ul><ol><li><h2><h3><h4>');
                                    $safeContent = nl2br($safeContent);
                                    $safeContent = str_replace('<h2>', '<h2 class="text-2xl font-black text-[#0b276b] border-b-2 border-slate-100 pb-3 mb-4 mt-8 uppercase tracking-tight">', $safeContent);
                                    $safeContent = str_replace('<h3>', '<h3 class="text-xl font-bold text-slate-800 mb-3 mt-6">', $safeContent);
                                    $safeContent = str_replace('<p>', '<p class="mb-4 text-slate-600 font-medium leading-loose text-justify">', $safeContent);
                                    $safeContent = str_replace('<ul>', '<ul class="list-disc list-inside mb-6 space-y-2 text-slate-600 font-medium">', $safeContent);
                                @endphp
                                
                                <article class="max-w-none">
                                    {!! $safeContent ?: '<div class="text-center py-20"><i class="fa-solid fa-ghost text-6xl text-slate-200 mb-4"></i><p class="text-slate-400 font-black uppercase tracking-widest">Modul ini masih kosong.</p></div>' !!}
                                </article>
                            </div>

                            <div x-show="activeTab === 'kode'" 
                                 x-transition:enter="transition ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 translate-y-4" 
                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                 style="display: none;" 
                                 class="h-full flex flex-col"
                                 x-data="{ 
                                     rawCode: @js($material->code_visualization ?? '// GM lu kelupaan ngasih contoh kode. \n// Selamat ngulik di angan-angan!'),
                                     displayedCode: '',
                                     isPlaying: false,
                                     isFinished: false,
                                     startSimulation() {
                                         if(this.isPlaying) return;
                                         this.isPlaying = true;
                                         this.isFinished = false;
                                         this.displayedCode = '';
                                         let i = 0;
                                         let speed = 25; // Kecepatan ngetik
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
                    
                    <div class="bg-[#0b276b] text-white rounded-2xl p-6 shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 opacity-10 text-8xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-swords"></i>
                        </div>
                        <h3 class="font-black text-lg mb-2 relative z-10 uppercase tracking-tight text-amber-400">Quiz Terbuka</h3>
                        <p class="text-blue-100 text-xs font-bold leading-relaxed mb-6 relative z-10">Karena lu udah buka materi ini, gembok kuis otomatis kebuka. Buktikan kalau kamu ngga cuma numpang lewat!</p>
                        <a href="{{ route('student.tasks.index') }}" class="block w-full bg-amber-500 hover:bg-amber-600 text-slate-900 text-center font-black uppercase tracking-widest py-3 rounded-xl shadow-[0_4px_0_0_#b45309] hover:shadow-[0_2px_0_0_#b45309] hover:translate-y-[2px] transition-all relative z-10 text-xs">
                            Kerjain Quiz
                        </a>
                    </div>

                    @if($material->pdf_path)
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:border-blue-400 transition-all">
                        <div class="absolute -right-4 -bottom-4 opacity-10 text-8xl group-hover:scale-110 group-hover:-rotate-12 transition-all">
                            <i class="fa-solid fa-scroll"></i>
                        </div>
                        <h3 class="font-black text-blue-900 mb-2 relative z-10 flex items-center space-x-2 uppercase tracking-tight">
                            <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                            <span>Dokumen Materi</span>
                        </h3>
                        <p class="text-blue-700 text-xs font-bold mb-6 relative z-10 leading-relaxed">Dosen ngasih materi versi PDF. Download biar bisa dibaca pas kuota sekarat.</p>
                        
                        <a href="{{ asset('storage/' . $material->pdf_path) }}" target="_blank" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-black uppercase tracking-widest py-3 rounded-xl shadow-[0_4px_0_0_#1e3a8a] hover:shadow-[0_2px_0_0_#1e3a8a] hover:translate-y-[2px] transition-all relative z-10 text-xs flex items-center justify-center">
                            <i class="fa-solid fa-download mr-2"></i> Unduh PDF
                        </a>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 shadow-sm hover:border-emerald-300 transition-colors">
                        <h3 class="font-black text-slate-800 mb-3 flex items-center space-x-2 uppercase tracking-tight">
                            <i class="fa-solid fa-comments text-emerald-500"></i>
                            <span>Ruang Curhat</span>
                        </h3>
                        <p class="text-xs font-bold text-slate-500 mb-6 leading-relaxed">Ada kodingan yang bikin otak lu ngebul? Tanya aja di forum, jangan dipendam sendiri entar stres.</p>
                        <button class="w-full border-2 border-slate-200 text-slate-500 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 font-black uppercase tracking-widest py-3 rounded-xl transition-all text-xs">
                            Buka Forum
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </main>
</div>
@endsection