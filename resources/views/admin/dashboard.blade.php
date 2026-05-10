@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between hidden md:flex shrink-0">
        <div class="flex-1 overflow-y-auto">
            <div class="h-20 flex items-center px-6 border-b border-slate-800 mb-6 sticky top-0 bg-slate-900 z-10">
                <div class="flex items-center space-x-3 text-white">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg shadow-lg shadow-indigo-600/20">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg leading-tight tracking-wide">AlgoAdmin</h2>
                    </div>
                </div>
            </div>

            <nav class="px-4 space-y-2 mb-4">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 bg-indigo-600 text-white rounded-lg font-medium text-sm shadow-md">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-book w-5 text-center"></i>
                    <span>Kelola Materi</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-file-signature w-5 text-center"></i>
                    <span>Bank Kuis</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span>Data Mahasiswa</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Laporan Nilai</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-gear w-5 text-center"></i>
                    <span>Pengaturan Sistem</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 space-y-1 bg-slate-900 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-red-500/10 hover:text-red-500 rounded-lg font-medium text-sm w-full transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 flex justify-between items-center px-8 shrink-0 border-b border-slate-200 bg-white/80 backdrop-blur-md z-10">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-slate-800 hidden sm:block">Control Panel</h1>
            </div>
            <div class="flex items-center space-x-6">
                <button class="text-slate-400 hover:text-indigo-600 relative transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 capitalize">Administrator</p>
                    </div>
                    <a href="#" class="w-10 h-10 rounded-full bg-indigo-600 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-white font-bold hover:ring-2 hover:ring-indigo-300 transition-all cursor-pointer">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </a>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up">
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Total Mahasiswa</p>
                            <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\User::where('role', 'student')->count() }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Materi Aktif</p>
                            <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\Material::count() }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-clipboard-question"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Kuis Tersedia</p>
                            <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\Quiz::count() }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Remedial Aktif</p>
                            <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\RemedialRecommendation::where('is_completed', false)->count() }}</h3>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-800">Pendaftar Terbaru</h3>
                            <button class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                        <th class="px-6 py-4 font-medium">Nama Mahasiswa</th>
                                        <th class="px-6 py-4 font-medium">Email</th>
                                        <th class="px-6 py-4 font-medium">Level</th>
                                        <th class="px-6 py-4 font-medium">Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @php
                                        $recentStudents = \App\Models\User::where('role', 'student')->latest()->take(5)->get();
                                    @endphp
                                    @forelse($recentStudents as $student)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-slate-800 flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <span>{{ $student->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">{{ $student->email }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                {{ $student->getLevel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">{{ $student->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada mahasiswa yang mendaftar. Kasihan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                            <h3 class="font-bold text-slate-800">Aktivitas Sistem</h3>
                        </div>
                        <div class="p-6">
                            <div class="relative border-l border-slate-200 ml-3 space-y-6">
                                <div class="relative pl-6">
                                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white"></div>
                                    <p class="text-sm font-medium text-slate-800">Budi menyelesaikan Pretest</p>
                                    <p class="text-xs text-slate-500 mt-1">10 menit yang lalu</p>
                                </div>
                                <div class="relative pl-6">
                                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-blue-500 ring-4 ring-white"></div>
                                    <p class="text-sm font-medium text-slate-800">Materi "Pointer C++" diperbarui</p>
                                    <p class="text-xs text-slate-500 mt-1">1 jam yang lalu</p>
                                </div>
                                <div class="relative pl-6">
                                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-orange-500 ring-4 ring-white"></div>
                                    <p class="text-sm font-medium text-slate-800">Sistem mendeteksi 3 mahasiswa butuh remedial</p>
                                    <p class="text-xs text-slate-500 mt-1">Kemarin, 14:30</p>
                                </div>
                                <div class="relative pl-6">
                                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-indigo-500 ring-4 ring-white"></div>
                                    <p class="text-sm font-medium text-slate-800">Backup database otomatis berhasil</p>
                                    <p class="text-xs text-slate-500 mt-1">Kemarin, 00:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection