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

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ 
    showAddModal: false, 
    showEditModal: false, editUrl: '', editData: { question: '', option_a: '', option_b: '', option_c: '', option_d: '', correct_answer: 'a' },
    showDeleteModal: false, deleteUrl: ''
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
                <span class="text-sm font-bold text-slate-500">GM {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto animate-fade-in-up">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <div class="inline-block bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm mb-2">Ujian Pertama</div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Kelola Pretest</h1>
                        <p class="text-slate-500 font-medium text-sm mt-1">Nambahin soal pretest langsung dari sini biar maba pusing.</p>
                    </div>
                    <button @click="showAddModal = true" class="bg-emerald-500 hover:bg-emerald-600 text-white font-black py-3 px-6 rounded-2xl shadow-[0_4px_0_0_#059669] hover:translate-y-[2px] transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-plus-circle"></i><span>Tambah Soal</span>
                    </button>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i><span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4">
                    @forelse($pretests as $index => $item)
                        <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 flex flex-col md:flex-row md:items-center justify-between hover:border-amber-300 transition-all group">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-black text-xs">{{ $index + 1 }}</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Soal Pretest</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $item->question }}</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-[10px] font-bold">
                                    <div class="{{ $item->correct_answer == 'a' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-400' }} p-2 rounded-lg border border-slate-100">A: {{ $item->option_a }}</div>
                                    <div class="{{ $item->correct_answer == 'b' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-400' }} p-2 rounded-lg border border-slate-100">B: {{ $item->option_b }}</div>
                                    <div class="{{ $item->correct_answer == 'c' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-400' }} p-2 rounded-lg border border-slate-100">C: {{ $item->option_c }}</div>
                                    <div class="{{ $item->correct_answer == 'd' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-400' }} p-2 rounded-lg border border-slate-100">D: {{ $item->option_d }}</div>
                                </div>
                            </div>
                            <div class="flex space-x-2 mt-4 md:mt-0 md:ml-6">
                                <button @click="
                                    editUrl = '{{ route('lecturer.pretest.update', $item->id) }}';
                                    editData = { 
                                        question: '{{ addslashes($item->question) }}', 
                                        option_a: '{{ addslashes($item->option_a) }}', 
                                        option_b: '{{ addslashes($item->option_b) }}', 
                                        option_c: '{{ addslashes($item->option_c) }}', 
                                        option_d: '{{ addslashes($item->option_d) }}', 
                                        correct_answer: '{{ $item->correct_answer }}' 
                                    };
                                    showEditModal = true;
                                " class="p-3 bg-amber-100 text-amber-700 rounded-xl hover:bg-amber-200 transition-colors">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button @click="deleteUrl = '{{ route('lecturer.pretest.destroy', $item->id) }}'; showDeleteModal = true;" class="p-3 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                            <i class="fa-solid fa-ghost text-6xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-black uppercase tracking-widest">Pretest Kosong Lek!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <div x-show="showAddModal || showEditModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-3xl w-full max-w-2xl p-8 relative z-[1000] shadow-2xl border border-slate-100" @click.away="showAddModal = false; showEditModal = false">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800" x-text="showAddModal ? 'Racik Soal Baru' : 'Koreksi Soal'"></h3>
                <button @click="showAddModal = false; showEditModal = false" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form :action="showAddModal ? '{{ route('lecturer.pretest.store') }}' : editUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="showEditModal"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-1">Pertanyaan</label>
                    <textarea name="question" :value="showAddModal ? '' : editData.question" required class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-300 outline-none" rows="3"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(['a','b','c','d'] as $opt)
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 mb-1">Opsi {{ strtoupper($opt) }}</label>
                        <input type="text" name="option_{{ $opt }}" :value="showAddModal ? '' : editData.option_{{ $opt }}" required class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 text-sm outline-none">
                    </div>
                    @endforeach
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-1 text-center">Kunci Jawaban</label>
                    <div class="flex justify-center space-x-4 mt-2">
                        @foreach(['a','b','c','d'] as $opt)
                        <label class="cursor-pointer group">
                            <input type="radio" name="correct_answer" value="{{ $opt }}" class="hidden peer" :checked="showAddModal ? '{{ $opt == 'a' }}' : editData.correct_answer == '{{ $opt }}'">
                            <div class="w-12 h-12 rounded-xl border-2 border-slate-100 flex items-center justify-center font-black uppercase text-slate-400 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all">{{ $opt }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="w-full py-4 bg-slate-900 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#475569] hover:translate-y-[2px] transition-all">Simpan Soal</button>
            </form>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-8 text-center" @click.away="showDeleteModal = false">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm"><i class="fa-solid fa-trash-can text-2xl"></i></div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">Buang Soal Ini?</h3>
            <p class="text-slate-500 text-sm mt-2 mb-6">Maba bakal selamet kalau soal ini lu hapus lek. Yakin?</p>
            <form :action="deleteUrl" method="POST" class="flex flex-col space-y-2">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-xl shadow-[0_4px_0_0_#991b1b]">Musnahkan!</button>
                <button type="button" @click="showDeleteModal = false" class="w-full py-3 text-slate-500 font-bold">Batal</button>
            </form>
        </div>
    </div>
</div>
@endsection