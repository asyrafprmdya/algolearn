@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        {{-- HEADER --}}
        @include('admin.partials.header', ['title' => 'Dashboard'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-8">

                {{-- Alert Flash --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- STAT CARDS --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 animate-fade-in-up">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Total Mahasiswa</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_students'] }}</h3>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Dosen</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_lecturers'] }}</h3>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Materi</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_materials'] }}</h3>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-clipboard-question"></i>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Kuis</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_quizzes'] }}</h3>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Remedial Aktif</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $stats['remedial_active'] }}</h3>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-check-to-slot"></i>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Selesai Pretest</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_pretes'] }}</h3>
                    </div>
                </div>

                {{-- DISTRIBUSI LEVEL --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center space-x-2">
                            <i class="fa-solid fa-chart-pie text-indigo-500"></i>
                            <span>Distribusi Level Mahasiswa</span>
                        </h3>
                        @php $total = array_sum($levelDistribution) ?: 1; @endphp
                        <div class="space-y-4">
                            @foreach([
                                ['label' => 'Level 1 - Pemula',   'key' => 'Pemula',   'color' => 'bg-blue-500'],
                                ['label' => 'Level 2 - Menengah', 'key' => 'Menengah', 'color' => 'bg-indigo-500'],
                                ['label' => 'Level 3 - Lanjutan', 'key' => 'Lanjutan', 'color' => 'bg-purple-500'],
                            ] as $item)
                            @php
                                $count = $levelDistribution[$item['key']];
                                $pct   = round($count / $total * 100);
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                                    <span class="text-slate-500">{{ $count }} ({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="{{ $item['color'] }} h-2 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PENDAFTAR TERBARU --}}
                    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800">Pendaftar Terbaru</h3>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Nama</th>
                                        <th class="px-6 py-3 text-left">Email</th>
                                        <th class="px-6 py-3 text-left">Level</th>
                                        <th class="px-6 py-3 text-left">Pretest</th>
                                        <th class="px-6 py-3 text-left">Daftar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentUsers as $user)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-3 font-medium text-slate-800">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-slate-500">{{ $user->email }}</td>
                                        <td class="px-6 py-3">
                                            @php
                                                $lc = ['Pemula' => 'blue', 'Menengah' => 'indigo', 'Lanjutan' => 'purple'][$user->level] ?? 'slate';
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $lc }}-100 text-{{ $lc }}-700">
                                                {{ $user->level }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3">
                                            {{-- INI PENYAKIT HALU LU, UDAH GUE BENERIN! --}}
                                            @if($user->has_completed_pretest)
                                                <span class="text-emerald-600"><i class="fa-solid fa-check-circle"></i> Selesai</span>
                                            @else
                                                <span class="text-slate-400"><i class="fa-regular fa-clock"></i> Belum</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada mahasiswa terdaftar.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- AKSI CEPAT --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-xl text-indigo-700 transition-colors text-center">
                            <i class="fa-solid fa-user-plus text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Tambah Pengguna</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}?role=student" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl text-blue-700 transition-colors text-center">
                            <i class="fa-solid fa-users text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Daftar Mahasiswa</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex flex-col items-center p-4 bg-amber-50 hover:bg-amber-100 rounded-xl text-amber-700 transition-colors text-center">
                            <i class="fa-solid fa-sliders text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Ambang Batas</span>
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex flex-col items-center p-4 bg-emerald-50 hover:bg-emerald-100 rounded-xl text-emerald-700 transition-colors text-center">
                            <i class="fa-solid fa-file-export text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Ekspor Laporan</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection