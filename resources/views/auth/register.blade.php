@extends('layouts.app')
@section('content')
<style>
    @keyframes float-slow {
        0%, 100% { transform: translateY(0) rotate(2deg); }
        50% { transform: translateY(-15px) rotate(-2deg); }
    }
    .animate-float-slow {
        animation: float-slow 4s ease-in-out infinite;
    }
</style>

@if($errors->any() || session('error'))
<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 -translate-y-10 scale-90"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 -translate-y-10 scale-90"
     class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[100] w-[90%] max-w-md">

    <div class="bg-red-500 text-white px-6 py-4 rounded-[1.5rem] shadow-[0_8px_0_0_#991b1b] border-2 border-red-400 flex items-center space-x-4 hover:translate-y-[2px] hover:shadow-[0_6px_0_0_#991b1b] transition-all cursor-pointer" @click="show = false">
        <div class="bg-red-600 rounded-xl w-12 h-12 flex items-center justify-center shrink-0 border-2 border-red-400 shadow-inner text-2xl animate-bounce">
            <i class="fa-solid fa-skull-crossbones"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-black text-xl tracking-wider uppercase mb-1 drop-shadow-md">Wasted!</h4>
            <p class="font-bold text-red-100 text-sm leading-tight">
                @if(session('error'))
                    {{ session('error') }}
                @else
                    {{ $errors->first() ?: 'Cek lagi form lu lek, ada yang salah tuh!' }}
                @endif
            </p>
        </div>
        <div class="text-red-300 hover:text-white transition-colors shrink-0">
            <i class="fa-solid fa-xmark text-xl"></i>
        </div>
    </div>
</div>
@endif

<div class="flex min-h-screen bg-slate-50 font-sans">
    
    <div class="hidden lg:flex flex-col justify-center items-center w-1/2 bg-emerald-500 text-white p-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="relative z-10 w-full max-w-lg">
            <div class="inline-flex items-center space-x-3 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-2xl border border-white/30 mb-10 shadow-lg">
                <div class="w-10 h-10 bg-[#0b276b] rounded-full flex items-center justify-center text-white text-xl shadow-[0_3px_0_0_#061a4f]">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <span class="text-2xl font-black tracking-wide">AlgoLearn</span>
            </div>
            
            <h1 class="text-5xl font-black leading-tight mb-6 animate-float-slow">Bikin Akun,<br>Mulai Petualangan! 🎯</h1>
            <p class="text-emerald-100 text-lg font-medium leading-relaxed mb-8">Satu langkah lagi buat gabung sama mahasiswa lain yang udah pusing duluan ngerjain kuis. Jangan sampai ketinggalan level!</p>
            
            <div class="flex space-x-4">
                <div class="w-14 h-14 bg-white text-emerald-500 rounded-2xl flex items-center justify-center text-2xl shadow-[0_4px_0_0_#a7f3d0] -rotate-12">
                    <i class="fa-solid fa-scroll"></i>
                </div>
                <div class="w-14 h-14 bg-[#0b276b] text-white rounded-2xl flex items-center justify-center text-2xl shadow-[0_4px_0_0_#061a4f] rotate-6">
                    <i class="fa-solid fa-rocket"></i>
                </div>
            </div>
        </div>
        
        <div class="absolute -bottom-10 -right-10 opacity-20 pointer-events-none">
            <i class="fa-solid fa-puzzle-piece text-[30rem]"></i>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12 overflow-y-auto">
        <div class="w-full max-w-md my-auto py-8">
            
            <div class="lg:hidden flex items-center space-x-3 mb-8 justify-center">
                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xl shadow-md">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-wide">AlgoLearn</span>
            </div>

            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-slate-800 mb-2">Daftar Akun Baru</h2>
                <p class="text-slate-500 font-medium">Isi data yang bener, jangan pakai nama alay.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-user text-slate-400 text-lg"></i>
                        </div>
                        <input type="text" name="name" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 text-slate-800 font-medium transition-all text-lg" placeholder="Udin Sedunia" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Akademik</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-slate-400 text-lg"></i>
                        </div>
                        <input type="email" name="email" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 text-slate-800 font-medium transition-all text-lg" placeholder="nama@institusi.ac.id" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-lg"></i>
                        </div>
                        <input type="password" name="password" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 text-slate-800 font-medium transition-all text-lg" placeholder="Minimal 8 Karakter" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Konfirmasi Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-double text-slate-400 text-lg"></i>
                        </div>
                        <input type="password" name="password_confirmation" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 text-slate-800 font-medium transition-all text-lg" placeholder="Ketik ulang sandinya" required>
                    </div>
                </div>
                

                <div class="pt-4">
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-lg py-4 px-6 rounded-2xl uppercase tracking-wider transition-all shadow-[0_6px_0_0_#059669] hover:shadow-[0_2px_0_0_#059669] hover:translate-y-[4px] flex justify-center items-center space-x-2">
                        <span>Daftar Sekarang</span>
                        <i class="fa-solid fa-user-check"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center font-bold text-slate-500">
                Udah punya akun? 
                <a href="{{ route('login') }}" class="text-[#0b276b] hover:text-blue-900 transition-colors ml-1">Masuk sini lek</a>
            </div>
        </div>
    </div>
</div>
@endsection