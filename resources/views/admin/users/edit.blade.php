@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('admin.partials.header', ['title' => 'Edit Pengguna'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-2xl mx-auto">
                <div class="mb-6">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:underline">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Pengguna
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
                    <div class="flex items-center space-x-4 mb-6 pb-6 border-b border-slate-100">
                        <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            <span class="text-xs text-slate-400">Terdaftar {{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6 text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                        @csrf @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Peran <span class="text-red-500">*</span></label>
                            <select name="role" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="student" @selected(old('role', $user->role) === 'student')>Mahasiswa</option>
                                <option value="lecturer" @selected(old('role', $user->role) === 'lecturer')>Dosen</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
                            <p class="text-sm font-medium text-slate-700 mb-3">
                                <i class="fa-solid fa-lock text-slate-400 mr-1"></i>
                                Ganti Password (kosongkan jika tidak ingin mengubah)
                            </p>
                            <div class="space-y-3">
                                <input type="password" name="password"
                                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white"
                                       placeholder="Password baru (min. 8 karakter)">
                                <input type="password" name="password_confirmation"
                                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white"
                                       placeholder="Konfirmasi password baru">
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-slate-100">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
