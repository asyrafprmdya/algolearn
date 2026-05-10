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
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('lecturer.dashboard') ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Ringkasan Kelas</span>
                </a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('lecturer.materials.*') ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-square-plus w-5 text-center"></i>
                    <span>Kelola Materi</span>
                </a>
                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('lecturer.students.progress') ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-lg font-medium text-sm transition-colors">
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
            <div class="flex items-center space-x-6">
                <div class="w-10 h-10 rounded-full bg-amber-500 border-2 border-white shadow-sm flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up">
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Total Mahasiswa Aktif</p>
                            <h3 class="text-3xl font-bold text-slate-800">{{ $totalStudents }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Modul Materi Dibuat</p>
                            <h3 class="text-3xl font-bold text-slate-800">{{ $totalMaterials }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-bold text-slate-800">Aktivitas Kuis Mahasiswa Terbaru</h3>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-500 text-xs uppercase border-b border-slate-200">
                                <th class="px-6 py-4">Nama Mahasiswa</th>
                                <th class="px-6 py-4">Kuis</th>
                                <th class="px-6 py-4">Skor</th>
                                <th class="px-6 py-4">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($recentResults as $result)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $result->user->name ?? 'Anonim' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $result->quiz->title ?? 'Kuis Dihapus' }}</td>
                                <td class="px-6 py-4 font-bold {{ $result->score >= ($result->quiz->passing_grade ?? 70) ? 'text-emerald-500' : 'text-red-500' }}">
                                    {{ $result->score }}
                                </td>
                                <td class="px-6 py-4 text-slate-400">{{ $result->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada mahasiswa yang ngerjain kuis. Pada tidur kali.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Kelola Materi & Kuis</h3>
                        <a href="{{ route('lecturer.materials.create') }}" class="text-xs font-bold text-white bg-amber-500 px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors shadow-sm"><i class="fa-solid fa-plus mr-1"></i> Tambah Materi</a>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-500 text-xs uppercase border-b border-slate-200">
                                <th class="px-6 py-4">Judul Materi</th>
                                <th class="px-6 py-4">Level</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($materials as $material)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $material->title }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase border border-slate-200">
                                        {{ $material->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($material->is_published)
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Published</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('lecturer.materials.edit', $material->id) }}" class="text-amber-500 hover:text-white hover:bg-amber-500 font-bold text-xs border border-amber-500 px-3 py-1.5 rounded-md transition-colors flex items-center">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                        </a>
                                        <a href="{{ route('lecturer.quiz.create', $material->id) }}" class="text-emerald-500 hover:text-white hover:bg-emerald-500 font-bold text-xs border border-emerald-500 px-3 py-1.5 rounded-md transition-colors flex items-center">
                                            <i class="fa-solid fa-clipboard-question mr-1"></i> Buat Kuis
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Anda belum membuat materi apa-apa. Bikin materi dulu sana!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection