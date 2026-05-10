@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shrink-0">
        <div class="flex-1 overflow-y-auto">
            <div class="h-20 flex items-center px-6 border-b border-slate-100 mb-4 sticky top-0 bg-white z-10">
                <div class="flex items-center space-x-3 text-[#0b276b]">
                    <div class="bg-[#0b276b] text-white p-2 rounded-lg">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg leading-tight">Ruang<br>Belajar</h2>
                    </div>
                </div>
            </div>
            
            <div class="px-6 mb-6">
                <p class="text-xs text-slate-400 mb-1">Level Anda</p>
                <p class="text-sm font-semibold text-[#0b276b] bg-[#ebf0fc] py-1 px-3 rounded-md inline-block">{{ Auth::user()->getLevel() }}</p>
            </div>

            <nav class="px-4 space-y-1 mb-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-border-all w-5 text-center"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('student.material.index') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-book-open w-5 text-center"></i>
                    <span>Kurikulum</span>
                </a>
                <a href="{{ route('student.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-[#0b276b] text-white rounded-lg font-medium text-sm">
                    <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                    <span>Tugas Saya</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-regular fa-comments w-5 text-center"></i>
                    <span>Diskusi</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-chart-simple w-5 text-center"></i>
                    <span>Nilai</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-regular fa-circle-question w-5 text-center"></i>
                    <span>Bantuan</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-200 space-y-1 bg-white shrink-0">
            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-[#0b276b] rounded-lg font-medium text-sm w-full transition-colors">
                <i class="fa-regular fa-user w-5 text-center"></i>
                <span>Profil Saya</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-red-600 rounded-lg font-medium text-sm w-full transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/50 backdrop-blur-sm z-10">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-slate-800">Daftar Evaluasi</h1>
            </div>
            <div class="flex items-center space-x-6">
                <button class="text-slate-400 hover:text-[#0b276b] relative transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>
                <div class="w-10 h-10 rounded-full bg-[#0b276b] border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto">
                
                <div class="mb-8 animate-fade-in-up">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Tugas & Kuis Anda</h1>
                    <p class="text-slate-500">Kuis di bawah ini terbuka karena Anda telah membaca materi terkait. Buktikan pemahamanmu!</p>
                </div>

                <div class="space-y-6 animate-fade-in-up">
                    @php $hasTasks = false; @endphp
                    
                    @foreach($accessedMaterials as $material)
                        @foreach($material->quizzes as $quiz)
                            @php $hasTasks = true; @endphp
                            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center hover:shadow-md transition-shadow gap-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 rounded-lg bg-[#ebf0fc] text-[#0b276b] flex items-center justify-center text-xl shrink-0 mt-1">
                                        <i class="fa-solid fa-list-check"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Terbuka</span>
                                            <span class="text-xs text-slate-500 font-medium">Materi: {{ $material->title }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800">{{ $quiz->title }}</h3>
                                        <p class="text-sm text-slate-500 mt-1"><i class="fa-solid fa-bullseye text-slate-400 mr-1"></i> Nilai Lulus (KKM): {{ $quiz->passing_grade }}</p>
                                    </div>
                                </div>
                                <div class="w-full sm:w-auto shrink-0">
                                    <a href="{{ route('student.material.show', $material->id) }}" class="block w-full text-center bg-[#0b276b] hover:bg-blue-900 text-white font-bold py-2.5 px-6 rounded-lg transition-colors">
                                        Kerjakan Kuis
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    @if(!$hasTasks)
                    <div class="bg-white rounded-xl border border-slate-200 p-12 shadow-sm flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-4xl mb-4">
                            <i class="fa-regular fa-face-laugh-beam"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Wah, Tugas Masih Kosong!</h3>
                        <p class="text-slate-500 max-w-md">Kuis baru akan muncul di sini setelah Anda membuka dan membaca materi di halaman kurikulum. Jangan malas baca ya!</p>
                        <a href="{{ route('student.material.index') }}" class="mt-6 text-[#0b276b] font-bold hover:underline">Jelajahi Materi <i class="fa-solid fa-arrow-right ml-1"></i></a>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>
</div>
@endsection