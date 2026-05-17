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
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ 
    showDeleteModal: false, deleteUrl: '', deleteName: '',
    showEditModal: false, editUrl: '', editName: '', editEmail: '', editRole: 'student',
    showImportModal: false, showFilterModal: false,
    filterRole: '{{ request('role') }}', filterRoleLabel: '{{ request('role') === 'student' ? 'Mahasiswa' : (request('role') === 'lecturer' ? 'Dosen' : (request('role') === 'admin' ? 'Admin' : 'Semua Peran')) }}',
    filterLevel: '{{ request('level') }}', filterLevelLabel: '{{ request('level') ? 'Level - ' . request('level') : 'Semua Level' }}',
    filterStatus: '{{ request('status') }}', filterStatusLabel: '{{ request('status') === 'active' ? 'Aktif' : (request('status') === 'inactive' ? 'Nonaktif' : 'Semua Status') }}',
    editRoleLabel: 'Mahasiswa',
    openFilterRole: false, openFilterLevel: false, openFilterStatus: false, openEditRole: false
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
                        <button @click="showFilterModal = true"
                                class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-100 flex items-center space-x-1.5 transition-colors shadow-sm">
                            <i class="fa-solid fa-filter"></i>
                            <span>Filter Data</span>
                        </button>
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
                                        @if($user->has_completed_pretest)
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
                                                    editRoleLabel = editRole === 'student' ? 'Mahasiswa' : (editRole === 'lecturer' ? 'Dosen' : 'Admin');
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

    <div x-show="showFilterModal" class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 relative z-[1000] shadow-2xl border border-slate-100" @click.away="showFilterModal = false">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800"><i class="fa-solid fa-filter text-indigo-500 mr-2"></i>Filter Pencarian</h3>
                <button @click="showFilterModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Pencarian Nama / Email</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                
                <div class="relative" @click.outside="openFilterRole = false">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Peran Pengguna</label>
                    <button type="button" @click="openFilterRole = !openFilterRole" class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-500 transition-colors">
                        <span x-text="filterRoleLabel"></span>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform" :class="openFilterRole ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFilterRole" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                        <button type="button" @click="filterRole = ''; filterRoleLabel = 'Semua Peran'; openFilterRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Semua Peran</button>
                        <button type="button" @click="filterRole = 'student'; filterRoleLabel = 'Mahasiswa'; openFilterRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Mahasiswa</button>
                        <button type="button" @click="filterRole = 'lecturer'; filterRoleLabel = 'Dosen'; openFilterRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Dosen</button>
                        <button type="button" @click="filterRole = 'admin'; filterRoleLabel = 'Admin'; openFilterRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Admin</button>
                    </div>
                    <input type="hidden" name="role" :value="filterRole">
                </div>

                <div class="relative" @click.outside="openFilterLevel = false">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kasta / Level Maba</label>
                    <button type="button" @click="openFilterLevel = !openFilterLevel" class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-500 transition-colors">
                        <span x-text="filterLevelLabel"></span>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform" :class="openFilterLevel ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFilterLevel" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                        <button type="button" @click="filterLevel = ''; filterLevelLabel = 'Semua Level'; openFilterLevel = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Semua Level</button>
                        <button type="button" @click="filterLevel = 'Pemula'; filterLevelLabel = 'Level - Pemula'; openFilterLevel = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Level 1 - Pemula</button>
                        <button type="button" @click="filterLevel = 'Menengah'; filterLevelLabel = 'Level - Menengah'; openFilterLevel = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Level 2 - Menengah</button>
                        <button type="button" @click="filterLevel = 'Lanjutan'; filterLevelLabel = 'Level - Lanjutan'; openFilterLevel = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Level 3 - Lanjutan</button>
                    </div>
                    <input type="hidden" name="level" :value="filterLevel">
                </div>

                <div class="relative" @click.outside="openFilterStatus = false">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status Akun</label>
                    <button type="button" @click="openFilterStatus = !openFilterStatus" class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-500 transition-colors">
                        <span x-text="filterStatusLabel"></span>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform" :class="openFilterStatus ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFilterStatus" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                        <button type="button" @click="filterStatus = ''; filterStatusLabel = 'Semua Status'; openFilterStatus = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Semua Status</button>
                        <button type="button" @click="filterStatus = 'active'; filterStatusLabel = 'Aktif'; openFilterStatus = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Aktif</button>
                        <button type="button" @click="filterStatus = 'inactive'; filterStatusLabel = 'Nonaktif'; openFilterStatus = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Nonaktif</button>
                    </div>
                    <input type="hidden" name="status" :value="filterStatus">
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-100 mt-6">
                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-center rounded-lg text-sm font-bold hover:bg-slate-200 transition-colors">
                        Reset Semua
                    </a>
                </div>
            </form>
        </div>
    </div>

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
                
                <div class="relative" @click.outside="openEditRole = false">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Peran <span class="text-red-500">*</span></label>
                    <button type="button" @click="openEditRole = !openEditRole" class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-500 transition-colors">
                        <span x-text="editRoleLabel"></span>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform" :class="openEditRole ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openEditRole" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                        <button type="button" @click="editRole = 'student'; editRoleLabel = 'Mahasiswa'; openEditRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Mahasiswa</button>
                        <button type="button" @click="editRole = 'lecturer'; editRoleLabel = 'Dosen'; openEditRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Dosen</button>
                        <button type="button" @click="editRole = 'admin'; editRoleLabel = 'Admin'; openEditRole = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Admin</button>
                    </div>
                    <input type="hidden" name="role" :value="editRole">
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