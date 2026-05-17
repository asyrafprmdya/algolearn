@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('admin.partials.header', ['title' => 'Laporan & Ekspor'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <div>
                    <h2 class="text-xl font-bold text-slate-800">Laporan Perkembangan Belajar</h2>
                    <p class="text-sm text-slate-500 mt-1">Pantau dan unduh data akademik mahasiswa. (FR-012 SRS)</p>
                </div>

                {{-- EKSPOR CEPAT --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Ekspor Data</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('admin.reports.export', ['type' => 'students', 'format' => 'csv']) }}"
                           class="flex items-center space-x-4 p-4 border border-slate-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-users text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Data Mahasiswa</p>
                                <p class="text-xs text-slate-500">Nama, email, level, status pretest (CSV)</p>
                            </div>
                            <i class="fa-solid fa-download text-slate-300 ml-auto group-hover:text-indigo-400"></i>
                        </a>

                        <a href="{{ route('admin.reports.export', ['type' => 'quiz_results', 'format' => 'csv']) }}"
                           class="flex items-center space-x-4 p-4 border border-slate-200 rounded-xl hover:border-emerald-300 hover:bg-emerald-50 transition-colors group">
                            <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                                <i class="fa-solid fa-clipboard-list text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Nilai Kuis</p>
                                <p class="text-xs text-slate-500">Seluruh hasil kuis per mahasiswa (CSV)</p>
                            </div>
                            <i class="fa-solid fa-download text-slate-300 ml-auto group-hover:text-emerald-400"></i>
                        </a>
                    </div>
                </div>

                {{-- TABEL MAHASISWA --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800">Ringkasan Data Mahasiswa</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left">Nama</th>
                                    <th class="px-6 py-3 text-left">Email</th>
                                    <th class="px-6 py-3 text-left">Level</th>
                                    <th class="px-6 py-3 text-left">Pretest</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($students->take(20) as $student)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3 font-medium text-slate-800">{{ $student->name }}</td>
                                    <td class="px-6 py-3 text-slate-500 text-xs">{{ $student->email }}</td>
                                    <td class="px-6 py-3">
                                        @php $lc = ['Pemula' => 'blue', 'Menengah' => 'indigo', 'Lanjutan' => 'purple'][$student->level] ?? 'slate'; @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $lc }}-100 text-{{ $lc }}-700">
                                            {{ $student->level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-xs">
                                        {{-- Di sini penyakit lu kemaren! Udah gue ganti ke has_completed_pretest --}}
                                        @if($student->has_completed_pretest)
                                            <span class="text-emerald-600"><i class="fa-solid fa-check-circle mr-1"></i>Selesai</span>
                                        @else
                                            <span class="text-slate-400"><i class="fa-regular fa-clock mr-1"></i>Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $student->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-slate-500 text-xs">{{ $student->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">Belum ada mahasiswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($students->count() > 20)
                            <p class="px-6 py-3 text-xs text-slate-400 border-t border-slate-100">
                                Menampilkan 20 dari {{ $students->count() }} mahasiswa. Unduh CSV untuk data lengkap.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- TABEL REMEDIAL --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800">Status Remedial Aktif</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left">Materi Remedial</th>
                                    <th class="px-6 py-3 text-left">Rekomendasi</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($remedials->take(15) as $r)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3 font-medium text-slate-800">{{ $r->user->name ?? '-' }}</td>
                                    <td class="px-6 py-3 text-slate-600">{{ $r->material->title ?? '-' }}</td>
                                    <td class="px-6 py-3 text-slate-500 text-xs max-w-xs truncate">{{ $r->recommendation_text }}</td>
                                    <td class="px-6 py-3">
                                        @if($r->is_completed)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Selesai</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-slate-500 text-xs">{{ $r->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <i class="fa-solid fa-check-circle text-emerald-400 text-2xl mb-2 block"></i>
                                        Tidak ada remedial aktif. Semua mahasiswa dalam kondisi baik!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection