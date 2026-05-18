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
                        {{ $isLecturer ? 'Dosen Pengampu' : Auth::user()->getLevel() }}
                    </p>
                </div>
            </div>

            <nav class="px-4 space-y-2 mb-4">
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
            </nav>
        </div>

        <div class="p-4 border-t {{ $isLecturer ? 'border-slate-800 bg-slate-900' : 'border-slate-100 bg-white' }} shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-4 {{ $isLecturer ? 'text-slate-500 hover:bg-red-900/30 hover:text-red-500' : 'text-slate-500 hover:bg-red-50 hover:text-red-600' }} rounded-2xl font-bold text-sm w-full transition-all group">
                    <i class="fa-solid fa-power-off w-6 text-center text-lg group-hover:rotate-90 transition-transform"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-slate-100" x-data="{ showDeleteModal: false, deleteActionUrl: '' }">
        <div class="flex-1 overflow-y-auto p-8 relative">
            <div class="max-w-6xl mx-auto animate-fade-in-up">
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Bank Kuis</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Daftar semua kuis yang udah lu bikin. Hati-hati pas ngerusak data!</p>
                </div>

                @foreach($materials as $material)
                <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm mb-6 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b-2 border-slate-200 flex justify-between items-center">
                        <h2 class="font-black text-slate-700 uppercase tracking-tight">{{ $material->title }}</h2>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $material->level }}</span>
                    </div>
                    <div class="p-6">
                        @if($material->quizzes->isEmpty())
                            <p class="text-center text-slate-400 font-bold text-sm py-4 italic">Belum ada kuis buat materi ini lek.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($material->quizzes as $quiz)
                                <div class="p-5 rounded-2xl border-2 {{ $quiz->category === 'evaluation' ? 'border-amber-200 bg-amber-50/30' : 'border-emerald-200 bg-emerald-50/30' }} flex justify-between items-center">
                                    <div>
                                        <p class="text-[10px] font-black {{ $quiz->category === 'evaluation' ? 'text-amber-500' : 'text-emerald-500' }} uppercase tracking-widest mb-1">
                                            {{ $quiz->category === 'evaluation' ? 'Latihan Pop-up' : 'Arena Evaluasi' }}
                                        </p>
                                        <h3 class="font-black text-slate-800 tracking-tight">{{ $quiz->title }}</h3>
                                        <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase">Lulus: {{ $quiz->passing_grade }}%</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('lecturer.quiz.edit', $quiz->id) }}" class="w-10 h-10 bg-white border-2 border-slate-200 text-blue-500 rounded-xl flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 transition-all shadow-sm">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button type="button" @click="showDeleteModal = true; deleteActionUrl = '{{ route('lecturer.quiz.destroy', $quiz->id) }}'" class="w-10 h-10 bg-white border-2 border-slate-200 text-red-500 rounded-xl flex items-center justify-center hover:border-red-500 hover:bg-red-50 transition-all shadow-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div x-show="showDeleteModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 w-full h-full" @click="showDeleteModal = false"></div>
            
            <form :action="deleteActionUrl" method="POST" class="bg-white rounded-3xl w-full max-w-sm p-8 relative z-[1000] shadow-2xl border-4 border-red-500 text-center" @click.stop x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-8">
                @csrf
                @method('DELETE')
                
                <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg rotate-12">
                    <i class="fa-solid fa-triangle-exclamation text-4xl -rotate-12"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Kuis?</h3>
                <p class="text-slate-500 font-bold mb-8 text-sm leading-relaxed">Lu yakin mau ngehangusin kuis ini dari muka bumi? Keringat maba lu yang udah ngerjain bakal ikut musnah kaga bisa di-<i>undo</i> lek!</p>
                
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-red-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#b91c1c] hover:translate-y-[2px] hover:shadow-[0_2px_0_0_#b91c1c] active:translate-y-[4px] active:shadow-none transition-all text-xs">
                        Ya, Musnahkan!
                    </button>
                    <button type="button" @click="showDeleteModal = false" class="w-full py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-all text-xs">
                        Batalin Aja
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection