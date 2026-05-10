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
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Ringkasan Kelas</span>
                </a>
                <a href="{{ route('lecturer.materials.create') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-square-plus w-5 text-center"></i>
                    <span>Input Materi</span>
                </a>
                <a href="{{ route('lecturer.students.progress') }}" class="flex items-center space-x-3 px-4 py-3 bg-amber-500 text-white rounded-lg font-medium text-sm">
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
            <div class="max-w-6xl mx-auto">
                <div class="mb-8 animate-fade-in-up">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Rapor Mahasiswa</h1>
                    <p class="text-slate-500">Pantau siapa yang rajin belajar dan siapa yang butuh disadarkan realita.</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in-up">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-xs uppercase border-b border-slate-200">
                                <th class="px-6 py-4 font-bold">Nama Mahasiswa</th>
                                <th class="px-6 py-4 font-bold">Email</th>
                                <th class="px-6 py-4 font-bold text-center">Level Pretest</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($students as $student)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-800 flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <span>{{ $student->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $student->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                        {{ $student->getLevel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-indigo-600 hover:text-indigo-800 font-bold text-xs underline">Lihat Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Daftar mahasiswa masih kosong. Kelasnya sepi amat.</td>
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