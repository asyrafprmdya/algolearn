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

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ showDeleteModal: false, deleteUrl: '', deleteTitle: '' }">
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
                <p class="text-[10px] font-black {{ $isLecturer ? 'text-slate-500' : 'text-slate-400' }} uppercase tracking-widest mb-2">Role: Dosen</p>
                <div class="inline-block">
                    <p class="text-xs font-black {{ $isLecturer ? 'text-amber-500 bg-amber-500/10 border-amber-500/20' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }} py-2 px-4 rounded-xl uppercase tracking-wide border-2 shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> DOSEN PENGAMPU
                    </p>
                </div>
            </div>

            <nav class="px-4 space-y-2 mb-4">
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

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold text-slate-500">Dosen {{ Auth::user()->name }}</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500 border-2 border-white shadow-md flex items-center justify-center text-white font-black text-lg">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto animate-fade-in-up">
                <div class="mb-8">
                    <div class="inline-block bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm mb-2">Pusat Evaluasi</div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Bank Kuis</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Kelola jebakan kuis berdasarkan materi yang udah lu rilis ke mahasiswa.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($materials as $material)
                        @php
                            $quiz = $material->quizzes->first();
                            $hasQuiz = $quiz !== null;
                            $kastaColor = match($material->level) {
                                'Pemula' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'Menengah' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'Lanjutan' => 'bg-purple-100 text-purple-700 border-purple-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm hover:border-amber-300 transition-all group relative overflow-hidden flex flex-col">
                            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform text-slate-900"><i class="fa-solid fa-dice-d20 text-8xl"></i></div>
                            <div class="flex justify-between items-start mb-4 relative z-10">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $kastaColor }} shadow-sm">{{ $material->level }}</span>
                                @if($hasQuiz)
                                    <span class="flex items-center space-x-1 text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100 text-[9px] font-black uppercase tracking-widest"><i class="fa-solid fa-check-circle"></i><span>Aktif</span></span>
                                @else
                                    <span class="flex items-center space-x-1 text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100 text-[9px] font-black uppercase tracking-widest"><i class="fa-solid fa-hourglass-half"></i><span>Kosong</span></span>
                                @endif
                            </div>
                            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2 line-clamp-2">{{ $material->title }}</h3>
                            <div class="flex-1">
                                @if($hasQuiz)
                                    <p class="text-xs font-bold text-slate-500 mb-6">Terdapat <span class="text-slate-800">{{ $quiz->questions->count() }}</span> soal yang siap menguji mental mahasiswa.</p>
                                @else
                                    <p class="text-xs font-bold text-slate-400 italic mb-6">Materi ini masih polos. Belum ada kuis buat ngetes maba.</p>
                                @endif
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-50 relative z-10">
                                @if($hasQuiz)
                                    <div class="grid grid-cols-1 gap-2">
                                        <a href="{{ route('lecturer.quiz.edit', $quiz->id) }}" class="w-full py-3 bg-amber-500 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-[0_4px_0_0_#b45309] hover:shadow-[0_2px_0_0_#b45309] hover:translate-y-[2px] transition-all flex items-center justify-center space-x-2">
                                            <i class="fa-solid fa-pen-to-square"></i><span>Edit Kuis</span>
                                        </a>
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="{{ route('lecturer.quiz.show', $quiz->id) }}" class="py-3 bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center space-x-2">
                                                <i class="fa-solid fa-eye"></i><span>Cek</span>
                                            </a>
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('lecturer.quiz.destroy', $quiz->id) }}'; deleteTitle = '{{ addslashes($quiz->title) }}'" type="button" class="py-3 bg-red-50 text-red-500 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all flex items-center justify-center space-x-2 border border-red-100">
                                                <i class="fa-solid fa-trash-can"></i><span>Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('lecturer.quiz.create', $material->id) }}" class="w-full py-4 bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-[0_4px_0_0_#059669] hover:shadow-[0_2px_0_0_#059669] hover:translate-y-[2px] transition-all flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-plus-circle"></i><span>Racik Kuis Sekarang</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                            <i class="fa-solid fa-box-open text-6xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-black uppercase tracking-widest">Kaga ada materi, kaga ada kuis. Bikin materi dulu sana!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <div x-show="showDeleteModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-md" style="display: none;" x-transition>
        <div class="bg-white rounded-[2.5rem] w-full max-w-md p-10 relative z-[1000] shadow-2xl border-2 border-slate-100 text-center" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg border-8 border-white">
                <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Hapus Kuis Ini?</h3>
            <p class="text-slate-500 font-medium mt-2 mb-8">Kuis <span class="font-bold text-slate-700" x-text="deleteTitle"></span> beserta nilai mahasiswa bakal hangus selamanya. Yakin?</p>
            
            <div class="flex flex-col space-y-3">
                <form :action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-4 bg-red-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#991b1b] hover:shadow-none hover:translate-y-[2px] transition-all">Hapus Permanen</button>
                </form>
                <button @click="showDeleteModal = false" class="w-full py-4 text-slate-400 font-black uppercase tracking-widest text-xs">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection