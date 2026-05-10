@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shrink-0">
        <div class="flex-1 overflow-y-auto">
            <div class="h-20 flex items-center px-6 border-b border-slate-100 mb-4 sticky top-0 bg-white z-10">
                <div class="flex items-center space-x-3 text-[#0b276b]">
                    <div class="bg-amber-500 text-white p-2 rounded-lg">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg leading-tight">Portal<br>Dosen</h2>
                    </div>
                </div>
            </div>

            <nav class="px-4 space-y-1 mb-4">
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-amber-500 text-white rounded-lg font-medium text-sm">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Ringkasan Kelas</span>
                </a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-square-plus w-5 text-center"></i>
                    <span>Input Materi</span>
                </a>
                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-users-viewfinder w-5 text-center"></i>
                    <span>Progres Mahasiswa</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-200 space-y-1 bg-white shrink-0">
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
        <header class="h-20 flex justify-end items-center px-8 shrink-0 border-b border-slate-200 bg-white/50 backdrop-blur-sm z-10">
            <div class="w-10 h-10 rounded-full bg-amber-500 border-2 border-white shadow-sm flex items-center justify-center text-white font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8 animate-fade-in-up">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Edit Materi</h1>
                    <p class="text-slate-500">Perbaiki *typo* atau tambahkan ilmu baru buat mahasiswamu.</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 animate-fade-in-up">
                    <form action="{{ route('lecturer.materials.update', $material->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Judul Materi</label>
                            <input type="text" name="title" value="{{ $material->title }}" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Target Level</label>
                            <select name="level" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white transition-colors cursor-pointer">
                                <option value="Pemula" {{ $material->level == 'Pemula' ? 'selected' : '' }}>Level Pemula (Basic)</option>
                                <option value="Menengah" {{ $material->level == 'Menengah' ? 'selected' : '' }}>Level Menengah (Intermediate)</option>
                                <option value="Lanjutan" {{ $material->level == 'Lanjutan' ? 'selected' : '' }}>Level Lanjutan (Advanced)</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Link Video Penjelasan (YouTube dll)</label>
                            <input type="url" name="video_url" value="{{ $material->video_url }}" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors text-blue-600">
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Visualisasi Kode Program (Opsional)</label>
                            <textarea name="code_visualization" rows="8" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-slate-900 text-green-400 font-mono text-sm leading-relaxed">{{ $material->code_visualization }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Konten / Penjelasan Modul</label>
                            <textarea name="content" required rows="10" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">{{ $material->content }}</textarea>
                        </div>

                        <div class="flex items-center mb-8 bg-slate-50 p-4 rounded-lg border border-slate-100">
                            <input type="checkbox" id="is_published" name="is_published" value="1" {{ $material->is_published ? 'checked' : '' }} class="w-5 h-5 text-amber-500 bg-white border-slate-300 rounded focus:ring-amber-500 cursor-pointer">
                            <label for="is_published" class="ml-3 text-sm font-bold text-slate-700 cursor-pointer">Terbitkan (Publish)</label>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('lecturer.dashboard') }}" class="px-6 py-3 border border-slate-300 text-slate-600 font-bold rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                                <i class="fa-solid fa-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection