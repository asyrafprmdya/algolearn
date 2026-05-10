@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('admin.partials.header', ['title' => 'Tambah Pengguna Baru'])

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-2xl mx-auto">
                <div class="mb-6">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:underline">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Pengguna
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Data Pengguna Baru</h2>

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6 text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('name') border-red-400 @enderror"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('email') border-red-400 @enderror"
                                   placeholder="email@contoh.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Peran <span class="text-red-500">*</span></label>
                            <select name="role" required
                                    class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="student" @selected(old('role') === 'student')>Mahasiswa</option>
                                <option value="lecturer" @selected(old('role') === 'lecturer')>Dosen</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('password') border-red-400 @enderror"
                                   placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                   placeholder="Ulangi password">
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-slate-100">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                                <i class="fa-solid fa-plus mr-1"></i> Buat Akun
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
