@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('admin.partials.header', ['title' => 'Manajemen Pengguna'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg">
                        <p class="font-medium">{{ session('warning') }}</p>
                        @if(session('import_errors'))
                            <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                                @foreach(session('import_errors') as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                {{-- TOOLBAR --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Daftar Pengguna</h2>
                        <p class="text-sm text-slate-500">Total {{ $users->total() }} pengguna terdaftar</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        {{-- Import CSV --}}
                        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                                class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 flex items-center space-x-1.5">
                            <i class="fa-solid fa-file-import"></i>
                            <span>Import CSV</span>
                        </button>
                        <a href="{{ route('admin.users.create') }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah Pengguna</span>
                        </a>
                    </div>
                </div>

                {{-- FILTER --}}
                <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Nama atau email..."
                                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Peran</label>
                            <select name="role" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">Semua Peran</option>
                                <option value="student" @selected(request('role') === 'student')>Mahasiswa</option>
                                <option value="lecturer" @selected(request('role') === 'lecturer')>Dosen</option>
                                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Level</label>
                            <select name="level" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">Semua Level</option>
                                <option value="Pemula" @selected(request('level') === 'Pemula')>Level 1 - Pemula</option>
                                <option value="Menengah" @selected(request('level') === 'Menengah')>Level 2 - Menengah</option>
                                <option value="Lanjutan" @selected(request('level') === 'Lanjutan')>Level 3 - Lanjutan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                            <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">Semua Status</option>
                                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50">
                            Reset
                        </a>
                    </div>
                </form>

                {{-- TABLE --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left">Pengguna</th>
                                    <th class="px-6 py-3 text-left">Peran</th>
                                    <th class="px-6 py-3 text-left">Level</th>
                                    <th class="px-6 py-3 text-left">Pretest</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Daftar</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($users as $user)
                                <tr class="hover:bg-slate-50 transition-colors {{ !$user->is_active ? 'opacity-60' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $roleColors = ['student' => 'blue', 'lecturer' => 'emerald', 'admin' => 'purple'];
                                            $roleLabels = ['student' => 'Mahasiswa', 'lecturer' => 'Dosen', 'admin' => 'Admin'];
                                            $rc = $roleColors[$user->role] ?? 'slate';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $rc }}-100 text-{{ $rc }}-700">
                                            {{ $roleLabels[$user->role] ?? $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-xs">{{ $user->level }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->pretest_completed)
                                            <span class="text-emerald-600 text-xs"><i class="fa-solid fa-check"></i> Selesai</span>
                                        @else
                                            <span class="text-slate-400 text-xs"><i class="fa-regular fa-clock"></i> Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_active)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Aktif</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="p-1.5 rounded-lg transition-colors {{ $user->is_active ? 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' }}"
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fa-solid {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>

                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        <i class="fa-solid fa-users-slash text-3xl mb-3 block"></i>
                                        Tidak ada pengguna ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

{{-- IMPORT MODAL --}}
<div id="importModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Import Mahasiswa dari CSV</h3>
            <p class="text-sm text-slate-500 mt-1">Format CSV: <code class="bg-slate-100 px-1 rounded">nama, email, password (opsional)</code></p>
        </div>
        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih File CSV</label>
                <input type="file" name="csv_file" accept=".csv,.txt" required
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700">
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-xs text-amber-700">
                <p class="font-semibold mb-1">Catatan:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Baris pertama adalah header (akan diabaikan)</li>
                    <li>Jika password tidak diisi, default: <code>password123</code></li>
                    <li>Email duplikat akan dilewati</li>
                </ul>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                    <i class="fa-solid fa-upload mr-1"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
