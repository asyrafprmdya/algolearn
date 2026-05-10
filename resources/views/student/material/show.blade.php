@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col" x-data="{ activeTab: 'video' }">
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center sticky top-0 z-40 shadow-sm">
        <a href="{{ route('student.dashboard') }}" class="mr-6 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#0b276b] hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <p class="text-xs font-bold text-[#0b276b] uppercase tracking-wider mb-1">Modul Pembelajaran</p>
            <h1 class="text-xl font-bold text-slate-800">{{ $material->title ?? 'Materi Belum Tersedia' }}</h1>
        </div>
        <div class="ml-auto flex items-center space-x-4">
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                {{ $material->level ?? 'Menengah' }}
            </span>
        </div>
    </header>

    <main class="flex-grow w-full max-w-5xl mx-auto py-8 px-4 flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-3/4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[600px]">
                
                <div class="flex border-b border-slate-200 bg-slate-50 overflow-x-auto shrink-0">
                    <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'border-[#0b276b] text-[#0b276b] bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100'" class="flex-1 py-4 px-4 text-sm font-bold border-b-2 transition-colors flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-circle-play"></i>
                        <span>Video Materi</span>
                    </button>
                    <button @click="activeTab = 'teks'" :class="activeTab === 'teks' ? 'border-[#0b276b] text-[#0b276b] bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100'" class="flex-1 py-4 px-4 text-sm font-bold border-b-2 transition-colors flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Modul Teks</span>
                    </button>
                    <button @click="activeTab = 'kode'" :class="activeTab === 'kode' ? 'border-[#0b276b] text-[#0b276b] bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100'" class="flex-1 py-4 px-4 text-sm font-bold border-b-2 transition-colors flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-code"></i>
                        <span>Visualisasi Kode</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto bg-white p-6 relative">
                    
                    <div x-show="activeTab === 'video'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="h-full flex flex-col">
                        @if($material->video_url)
                        <a href="{{ $material->video_url }}" target="_blank" class="w-full aspect-video bg-slate-900 rounded-lg overflow-hidden relative shadow-inner flex flex-col items-center justify-center group cursor-pointer hover:bg-slate-800 transition-colors border border-slate-700">
                            <i class="fa-brands fa-youtube text-red-600 text-7xl group-hover:scale-110 group-hover:text-red-500 transition-all mb-4 drop-shadow-lg"></i>
                            <span class="text-white font-bold tracking-wide">Klik untuk Menonton di YouTube</span>
                        </a>
                        @else
                        <div class="w-full aspect-video bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center text-slate-400">
                            <i class="fa-solid fa-video-slash text-5xl mb-3"></i>
                            <span class="font-medium text-sm">Dosen belum menyertakan link video.</span>
                        </div>
                        @endif
                        <div class="mt-6">
                            <h2 class="text-lg font-bold text-slate-800 mb-2">Penjelasan Visual</h2>
                            <p class="text-slate-600 text-sm leading-relaxed">Tonton video ini dulu biar ada gambaran, setelah itu lanjut baca teorinya di tab Modul Teks.</p>
                        </div>
                    </div>

                    <div x-show="activeTab === 'teks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @php
                            $rawContent = $material->content ?? '';
                            $safeContent = strip_tags($rawContent, '<b><i><u><strong><em><br><p><ul><ol><li>');
                            $safeContent = nl2br($safeContent);
                            
                            $safeContent = preg_replace('/\[SUB\](.*?)(\n|<br \/>|<br>|$)/i', '<h2 class="text-xl font-extrabold text-[#0b276b] border-b-2 border-slate-200 pb-2 mb-3 mt-8 flex items-center space-x-2"><i class="fa-solid fa-layer-group text-amber-500"></i><span>$1</span></h2>', $safeContent);

                            $safeContent = preg_replace_callback('/\[CODE\](.*?)\[\/CODE\]/is', function($matches) {
                                $code = str_replace(['<br />', '<br>'], '', $matches[1]);
                                $code = trim($code);
                                return '<div class="bg-[#1e1e1e] rounded-xl my-6 overflow-hidden shadow-md border border-slate-800 not-prose"><div class="bg-[#2d2d2d] px-4 py-3 flex items-center space-x-2 border-b border-black/50"><div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-slate-400 text-[10px] font-mono ml-4 uppercase tracking-widest">Code Snippet</span></div><div class="p-5 overflow-x-auto font-mono text-sm text-green-400 leading-relaxed whitespace-pre-wrap">' . $code . '</div></div>';
                            }, $safeContent);
                        @endphp
                        
                        <article class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-700">
                            {!! $safeContent ?: '<p class="text-slate-500 italic text-center mt-10">Konten belum tersedia.</p>' !!}
                        </article>
                    </div>

                    <div x-show="activeTab === 'kode'" 
                         x-transition:enter="transition ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         style="display: none;" 
                         class="h-full flex flex-col"
                         x-data="{ 
                             rawCode: @js($material->code_visualization ?? '// Dosennya lupa ngasih contoh kode. Selamat ngulik sendiri!'),
                             displayedCode: '',
                             isPlaying: false,
                             isFinished: false,
                             startSimulation() {
                                 if(this.isPlaying) return;
                                 this.isPlaying = true;
                                 this.isFinished = false;
                                 this.displayedCode = '';
                                 let i = 0;
                                 let speed = 30;
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
                        <div class="flex justify-between items-center mb-4 shrink-0">
                            <h3 class="font-bold text-slate-800">Simulasi Penulisan Kode</h3>
                            <button @click="startSimulation()" :disabled="isPlaying" class="bg-emerald-500 hover:bg-emerald-600 disabled:bg-emerald-300 text-white text-xs font-bold px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors shadow-sm">
                                <i class="fa-solid" :class="isPlaying ? 'fa-spinner fa-spin' : (isFinished ? 'fa-rotate-right' : 'fa-play')"></i>
                                <span x-text="isPlaying ? 'Mengetik...' : (isFinished ? 'Ulangi Simulasi' : 'Putar Simulasi')"></span>
                            </button>
                        </div>
                        <div class="bg-[#1e1e1e] rounded-xl flex-1 flex flex-col overflow-hidden shadow-inner border border-slate-800">
                            <div class="bg-[#2d2d2d] px-4 py-3 flex items-center space-x-2 border-b border-black/50 shrink-0">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                <span class="text-slate-400 text-[10px] font-mono ml-4 select-none">algolearn-terminal ~ bash</span>
                            </div>
                            <div id="code-terminal-body" class="p-5 flex-1 overflow-y-auto font-mono text-sm text-green-400 relative">
                                <div x-show="!isPlaying && !isFinished" class="absolute inset-0 flex flex-col items-center justify-center text-slate-500 opacity-50 pointer-events-none">
                                    <i class="fa-solid fa-laptop-code text-6xl mb-4"></i>
                                    <p class="font-sans text-sm font-medium">Klik "Putar Simulasi" di atas.</p>
                                </div>
                                <pre class="leading-relaxed whitespace-pre-wrap"><span x-text="displayedCode"></span><span x-show="isPlaying" class="inline-block w-2.5 h-4 bg-green-400 animate-pulse ml-1 align-middle"></span></pre>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/4 space-y-6">
            <div class="bg-[#0b276b] text-white rounded-xl p-6 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-8xl">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="font-bold text-lg mb-2 relative z-10">Tugas Menunggu</h3>
                <p class="text-blue-200 text-sm mb-6 relative z-10">Karena kamu sudah membuka materi ini, evaluasi sudah terbuka di menu Tugas Saya.</p>
                <a href="{{ route('student.tasks.index') }}" class="block w-full bg-emerald-500 hover:bg-emerald-600 text-center font-bold py-3 rounded-lg shadow-md transition-colors relative z-10 text-sm">
                    Kerjakan Kuis Sekarang
                </a>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center space-x-2">
                    <i class="fa-solid fa-comments text-[#0b276b]"></i>
                    <span>Diskusi Materi</span>
                </h3>
                <p class="text-sm text-slate-500 mb-4">Ada bagian kodingan yang bikin pusing? Tanya aja di forum, jangan dipendam sendiri.</p>
                <button class="w-full border-2 border-slate-200 text-slate-600 hover:border-[#0b276b] hover:text-[#0b276b] font-bold py-2 rounded-lg transition-colors">
                    Buka Forum Diskusi
                </button>
            </div>
        </div>

    </main>
</div>
@endsection