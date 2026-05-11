@extends('layouts.app')
@section('content')
<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: none; }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ 
    showDeleteModal: false, deleteUrl: '', deleteName: '',
    showEditModal: false, editUrl: '', editName: '', editEmail: '', editRole: 'student',
    showImportModal: false 
}">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('admin.partials.header', ['title' => 'Manajemen Pengguna'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6 animate-fade-in-up">

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
                        <p class="font-medium"><i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> {{ session('warning') }}</p>
                        @if(session('import_errors'))
                            <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                                @foreach(session('import_errors') as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center space-x-2 font-bold mb-2">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                            <span>Gagal menyimpan data! Silakan coba lagi.</span>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Daftar Pengguna</h2>
                        <p class="text-sm text-slate-500">Total {{ $users->total() }} pengguna terdaftar</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <button @click="showImportModal = true"
                                class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 flex items-center space-x-1.5 transition-colors">
                            <i class="fa-solid fa-file-import"></i>
                            <span>Import CSV</span>
                        </button>
                        <a href="{{ route('admin.users.create') }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 flex items-center space-x-1.5 transition-colors shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah Pengguna</span>
                        </a>
                    </div>
                </div>

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
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>

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
                                    <td class="px-6 py-4 text-slate-600 text-xs">{{ $user->level ?: '-' }}</td>
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
                                            <button @click="
                                                    editUrl = '{{ route('admin.users.update', $user) }}';
                                                    editName = '{{ addslashes($user->name) }}';
                                                    editEmail = '{{ addslashes($user->email) }}';
                                                    editRole = '{{ $user->role }}';
                                                    showEditModal = true;
                                                "
                                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Cepat">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="p-1.5 rounded-lg transition-colors {{ $user->is_active ? 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' }}"
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fa-solid {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>

                                            @if($user->id !== auth()->id())
                                                <button @click="
                                                        deleteUrl = '{{ route('admin.users.destroy', $user) }}';
                                                        deleteName = '{{ addslashes($user->name) }}';
                                                        showDeleteModal = true;
                                                    "
                                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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

    <div x-show="showEditModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl w-full max-w-md p-6 relative z-[1000] shadow-2xl border border-slate-100" @click.away="showEditModal = false">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800">Edit Pengguna</h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form :action="editUrl" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editName" required class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" x-model="editEmail" required class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Peran <span class="text-red-500">*</span></label>
                    <select name="role" x-model="editRole" class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="student">Mahasiswa</option>
                        <option value="lecturer">Dosen</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="bg-slate-50 rounded-lg p-3 border border-slate-100 mt-2">
                    <p class="text-xs font-medium text-slate-600 mb-2"><i class="fa-solid fa-lock text-slate-400 mr-1"></i>Ganti Password (opsional)</p>
                    <div class="space-y-2">
                        <input type="password" name="password" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Password baru">
                        <input type="password" name="password_confirmation" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Konfirmasi password">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition-colors">Simpan Perubahan</button>
                    <button type="button" @click="showEditModal = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-200 transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-8 relative z-[1000] shadow-2xl border-2 border-slate-100 text-center" @click.away="showDeleteModal = false">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                <i class="fa-solid fa-trash-can text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">Hapus Pengguna?</h3>
            <p class="text-slate-500 text-sm mt-2 mb-6">Yakin mau hapus akun <span class="font-bold text-slate-700" x-text="deleteName"></span>? Data yang udah musnah kaga bisa balik lagi lek.</p>
            <div class="flex flex-col space-y-2">
                <form :action="deleteUrl" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Ya, Musnahkan!</button>
                </form>
                <button @click="showDeleteModal = false" class="w-full py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
            </div>
        </div>
    </div>

    <div x-show="showImportModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" @click.away="showImportModal = false">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-800">Import Mahasiswa</h3>
                    <p class="text-xs text-slate-500 mt-1">Format: <code class="bg-slate-100 px-1 rounded">nama, email, password</code></p>
                </div>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-semibold">
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-xs text-amber-700">
                    <p class="font-semibold mb-1">Peringatan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Baris 1 (Header) otomatis diabaikan.</li>
                        <li>Password kosong bakal di-set: <code>password123</code>.</li>
                        <li>Email duplikat bakal dilewatin.</li>
                    </ul>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition-colors"><i class="fa-solid fa-upload mr-1"></i> Gas Import</button>
            </form>
        </div>
    </div>
</div>
@endsection