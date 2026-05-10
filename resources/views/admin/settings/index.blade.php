@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('admin.partials.header', ['title' => 'Konfigurasi Sistem'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div>
                    <h2 class="text-xl font-bold text-slate-800">Konfigurasi Sistem</h2>
                    <p class="text-sm text-slate-500 mt-1">Ubah ambang batas dan aturan sistem tanpa mengubah kode program. (Sesuai SRS section 6.a)</p>
                </div>

                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- RULE BASE PENENTUAN LEVEL --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Ambang Batas Penentuan Level (Rule Base)</h3>
                                <p class="text-xs text-slate-500">Digunakan saat mahasiswa menyelesaikan pretest</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4 mb-6 text-sm text-slate-600 border border-slate-100">
                            <p class="font-medium mb-2">Aturan penempatan level saat ini:</p>
                            <div class="space-y-1.5">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                    <span>Skor &lt; <strong>{{ $configs['threshold_level1'] }}%</strong> → <span class="font-semibold text-blue-700">Level 1 (Pemula)</span></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                                    <span>Skor {{ $configs['threshold_level1'] }}% – {{ $configs['threshold_level3'] }}% → <span class="font-semibold text-indigo-700">Level 2 (Menengah)</span></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-purple-500 shrink-0"></span>
                                    <span>Skor &gt; <strong>{{ $configs['threshold_level3'] }}%</strong> → <span class="font-semibold text-purple-700">Level 3 (Lanjutan)</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Batas Bawah Level 2 (%)
                                    <span class="text-slate-400 font-normal">– default: 40%</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="threshold_level1"
                                           value="{{ old('threshold_level1', $configs['threshold_level1']) }}"
                                           min="0" max="100" required
                                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('threshold_level1') border-red-400 @enderror">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Skor di bawah nilai ini → Level 1</p>
                                @error('threshold_level1')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Batas Bawah Level 3 (%)
                                    <span class="text-slate-400 font-normal">– default: 70%</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="threshold_level3"
                                           value="{{ old('threshold_level3', $configs['threshold_level3']) }}"
                                           min="0" max="100" required
                                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('threshold_level3') border-red-400 @enderror">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Skor di atas nilai ini → Level 3</p>
                                @error('threshold_level3')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- PASSING GRADE KUIS --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Nilai Lulus Kuis (Passing Grade)</h3>
                                <p class="text-xs text-slate-500">Nilai minimum untuk lulus kuis dan menghindari remedial</p>
                            </div>
                        </div>

                        <div class="max-w-xs">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nilai Lulus Kuis (%)
                                <span class="text-slate-400 font-normal">– default: 60%</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="quiz_passing_grade"
                                       value="{{ old('quiz_passing_grade', $configs['quiz_passing_grade']) }}"
                                       min="0" max="100" required
                                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Nilai di bawah ini akan memicu mekanisme remedial</p>
                        </div>
                    </div>

                    {{-- SISTEM --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="fa-solid fa-gear"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Pengaturan Sesi & Auto-Save</h3>
                                <p class="text-xs text-slate-500">Konfigurasi keamanan dan perlindungan data pengguna</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Timeout Sesi (jam)
                                    <span class="text-slate-400 font-normal">– default: 8</span>
                                </label>
                                <input type="number" name="session_timeout_hours"
                                       value="{{ old('session_timeout_hours', $configs['session_timeout_hours']) }}"
                                       min="1" max="24" required
                                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <p class="text-xs text-slate-400 mt-1">Sesi otomatis berakhir setelah tidak aktif selama ini</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Interval Auto-Save (detik)
                                    <span class="text-slate-400 font-normal">– default: 30</span>
                                </label>
                                <input type="number" name="autosave_interval_seconds"
                                       value="{{ old('autosave_interval_seconds', $configs['autosave_interval_seconds']) }}"
                                       min="10" max="120" required
                                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <p class="text-xs text-slate-400 mt-1">Jawaban pretest/kuis disimpan otomatis setiap interval ini</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>
@endsection
