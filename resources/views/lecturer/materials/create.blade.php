@extends('layouts.app')
@section('content')
<style>
    @keyframes pop-in {
        0% { opacity: 0; transform: scale(0.9) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-pop-in {
        animation: pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ 
    showLevelModal: false, 
    selectedLevel: 'Pemula',
    levelLabels: {
        'Pemula': 'LVL 1 - PEMULA',
        'Menengah': 'LVL 2 - MENENGAH',
        'Lanjutan': 'LVL 3 - LANJUTAN'
    }
}">
    
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
                    <i class="fa-solid fa-satellite-dish w-6 text-center text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('lecturer.materials.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-boxes-stacked w-6 text-center text-lg"></i>
                    <span>Kelola Materi</span>
                </a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.materials.create', 'lecturer.materials.edit') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-hammer w-6 text-center text-lg"></i>
                    <span>Buat Materi</span>
                </a>
                
                {{-- Tambahan menu pretest dan kuis sesuai request lek --}}
                <a href="{{ route('lecturer.pretest.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.pretest.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-scroll w-6 text-center text-lg"></i>
                    <span>Markas Pretest</span>
                </a>
                <a href="{{ route('lecturer.quiz.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-dungeon w-6 text-center text-lg"></i>
                    <span>Bank Kuis</span>
                </a>

                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('lecturer.students.progress') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-crosshairs w-6 text-center text-lg"></i>
                    <span>Laporan Mahasiswa</span>
                </a>
            @else
                <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.dashboard') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-border-all w-6 text-center text-lg"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('student.material.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.material.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-book-open w-6 text-center text-lg"></i>
                    <span>Kurikulum</span>
                </a>
                <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.tasks.*', 'student.quiz.*') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-clipboard-list w-6 text-center text-lg"></i>
                    <span>Tugas Saya</span>
                </a>
                <a href="{{ route('student.progress.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('student.progress.index') ? $activeMenu . ' translate-y-[-2px]' : $textMenu }}">
                    <i class="fa-solid fa-chart-line w-6 text-center text-lg"></i>
                    <span>Laporan Progress</span>
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
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500"> {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8 animate-fade-in-up">
                    <h1 class="text-3xl font-black text-slate-800 mb-2 uppercase tracking-tight">Buat Materi Baru</h1>
                    <p class="text-slate-500 font-medium text-sm">Masukan materi, upload PDF asli, dan tentukan level mahasiswamu.</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-8">
                    <form action="{{ route('lecturer.materials.store') }}" method="POST" id="quest-form" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest">Judul Materi</label>
                                <input type="text" name="title" required class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-50 text-slate-800 font-bold transition-all" placeholder="Misal: Algoritma Pencarian">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest">Target Level</label>
                                <input type="hidden" name="level" x-model="selectedLevel">
                                <button type="button" @click="showLevelModal = true" class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 bg-slate-50 flex items-center justify-between hover:border-amber-500 hover:bg-amber-50 transition-all group">
                                    <span class="text-slate-800 font-black text-sm uppercase" x-text="levelLabels[selectedLevel]"></span>
                                    <i class="fa-solid fa-wand-magic-sparkles text-amber-500 group-hover:scale-125 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-xs font-black text-slate-500 uppercase mb-3 ml-1 tracking-widest">Ringkasan Materi (Poin Penting)</label>
                            <input type="hidden" name="content" id="content-input">
                            <div class="border-2 border-slate-100 rounded-xl overflow-hidden shadow-inner">
                                <div id="editor-toolbar" class="bg-slate-50 border-b-2 border-slate-100 p-2">
                                    <span class="ql-formats">
                                        <select class="ql-header"><option value="2">H1</option><option value="3">H2</option><option selected>Normal</option></select>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-code-block"></button><button class="ql-clean"></button>
                                    </span>
                                </div>
                                <div id="editor-container" class="bg-white min-h-[250px] text-slate-700 font-medium px-4 py-2"></div>
                            </div>
                        </div>

                        <div class="mb-8 bg-blue-50 border-2 border-dashed border-blue-300 rounded-2xl p-8 text-center hover:bg-blue-100 transition-colors group relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 text-blue-200/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-file-pdf text-[10rem]"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-blue-500 text-2xl border-2 border-blue-200 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                </div>
                                <h3 class="font-black text-slate-700 mb-1 text-lg">Upload Materi Asli (File PDF)</h3>
                                <p class="text-xs text-slate-500 mb-6 font-bold uppercase tracking-wider">Opsional. Biarkan mahasiswa ngunduh materi full-nya.</p>
                                
                                <input type="file" name="pdf_file" accept=".pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer mx-auto max-w-sm transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Video URL (Opsional)</label>
                                <input type="url" name="video_url" class="w-full px-4 py-4 rounded-xl border-2 border-slate-100 focus:border-red-500 text-sm font-medium transition-all" placeholder="https://youtube.com/...">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Quick Code Snippet (Opsional)</label>
                                <input type="text" name="code_visualization" class="w-full px-4 py-4 rounded-xl border-2 border-slate-100 focus:border-emerald-500 font-mono text-xs transition-all" placeholder="int x = 10;">
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 mb-8">
                            <div class="flex items-center space-x-4">
                                <div class="bg-emerald-500 text-white p-2 rounded-lg"><i class="fa-solid fa-globe"></i></div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm">Status Publikasi</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Terbitkan langsung ke Mahasiswa</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" checked class="sr-only peer">
                                <div class="w-14 h-7 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all"></div>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-slate-900 font-black py-4 px-12 rounded-xl shadow-[0_6px_0_0_#b45309] hover:shadow-[0_2px_0_0_#b45309] hover:translate-y-[4px] transition-all flex items-center justify-center space-x-3">
                                <i class="fa-solid fa-fire"></i>
                                <span>UPLOAD MATERI</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div x-show="showLevelModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showLevelModal = false"></div>
        
        <div class="bg-white rounded-3xl w-full max-w-lg p-8 relative z-10 shadow-2xl animate-pop-in" @click.stop>
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-sm">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800">Tentukan Level Misi</h3>
                <p class="text-slate-500 text-sm font-medium">Siapa yang berhak mempelajari ilmu rahasia ini?</p>
            </div>

            <div class="space-y-4">
                <button @click="selectedLevel = 'Pemula'; showLevelModal = false" class="w-full p-5 rounded-2xl border-2 flex items-center justify-between group transition-all" :class="selectedLevel === 'Pemula' ? 'border-slate-400 bg-slate-50 shadow-inner' : 'border-slate-100 hover:border-amber-500 hover:bg-amber-50'">
                    <div class="flex items-center space-x-4 text-left">
                        <div class="w-12 h-12 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-xl font-black">1</div>
                        <div>
                            <p class="font-black text-slate-800 uppercase text-sm tracking-wide">Pemula (Basic)</p>
                            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-tighter">Terbuka untuk semua player</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-circle-check text-xl" :class="selectedLevel === 'Pemula' ? 'text-slate-600' : 'text-slate-100 group-hover:text-amber-200'"></i>
                </button>

                <button @click="selectedLevel = 'Menengah'; showLevelModal = false" class="w-full p-5 rounded-2xl border-2 flex items-center justify-between group transition-all" :class="selectedLevel === 'Menengah' ? 'border-blue-400 bg-blue-50 shadow-inner' : 'border-slate-100 hover:border-blue-500 hover:bg-blue-50'">
                    <div class="flex items-center space-x-4 text-left">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-black">2</div>
                        <div>
                            <p class="font-black text-blue-800 uppercase text-sm tracking-wide">Menengah (Intermediate)</p>
                            <p class="text-[11px] text-blue-400 font-bold uppercase tracking-tighter">Butuh pemahaman dasar</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-circle-check text-xl" :class="selectedLevel === 'Menengah' ? 'text-blue-600' : 'text-slate-100 group-hover:text-blue-200'"></i>
                </button>

                <button @click="selectedLevel = 'Lanjutan'; showLevelModal = false" class="w-full p-5 rounded-2xl border-2 flex items-center justify-between group transition-all" :class="selectedLevel === 'Lanjutan' ? 'border-emerald-400 bg-emerald-50 shadow-inner' : 'border-slate-100 hover:border-emerald-500 hover:bg-emerald-50'">
                    <div class="flex items-center space-x-4 text-left">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-black">3</div>
                        <div>
                            <p class="font-black text-emerald-800 uppercase text-sm tracking-wide">Lanjutan (Advanced)</p>
                            <p class="text-[11px] text-emerald-400 font-bold uppercase tracking-tighter">Hanya untuk level tertinggi</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-circle-check text-xl" :class="selectedLevel === 'Lanjutan' ? 'text-emerald-600' : 'text-slate-100 group-hover:text-emerald-200'"></i>
                </button>
            </div>
            
            <button @click="showLevelModal = false" class="w-full mt-8 py-4 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-slate-600 transition-colors">Tutup</button>
        </div>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tuliskan poin penting dari misi lu di sini...',
            modules: { toolbar: '#editor-toolbar' }
        });
        var form = document.getElementById('quest-form');
        form.onsubmit = function() {
            var contentInput = document.getElementById('content-input');
            contentInput.value = quill.root.innerHTML;
        };
    });
</script>
@endsection