@extends('layouts.app')
@section('content')
<style>
    @keyframes float-slow {
        0%, 100% { transform: translateY(0) rotate(-2deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
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
                    {{ $errors->first() ?: 'Username atau password lu salah lek! Typo atau memori lu penuh?' }}
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
    
    <div class="hidden lg:flex flex-col justify-center items-center w-1/2 bg-[#0b276b] text-white p-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="relative z-10 w-full max-w-lg">
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm px-6 py-3 rounded-2xl border border-white/20 mb-10 shadow-lg">
                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xl shadow-[0_3px_0_0_#059669]">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                <span class="text-2xl font-black tracking-wide">AlgoLearn</span>
            </div>
            
            <h1 class="text-5xl font-black leading-tight mb-6 animate-float-slow">Belajar Koding,<br>Rasa Main Game! 🚀</h1>
            <p class="text-blue-200 text-lg font-medium leading-relaxed mb-8">Nggak ada lagi cerita ketiduran pas baca modul. Selesaikan misi, kumpulkan skor, dan buktikan kasta lu di sini.</p>
            
            <div class="flex space-x-4">
                <div class="w-14 h-14 bg-red-500 rounded-2xl flex items-center justify-center text-2xl shadow-[0_4px_0_0_#b91c1c] rotate-12">
                    <i class="fa-solid fa-code"></i>
                </div>
                <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-2xl shadow-[0_4px_0_0_#d97706] -rotate-6">
                    <i class="fa-solid fa-bug-slash"></i>
                </div>
                <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center text-2xl shadow-[0_4px_0_0_#1d4ed8] rotate-6">
                    <i class="fa-solid fa-medal"></i>
                </div>
            </div>
        </div>
        
        <div class="absolute -bottom-20 -right-20 opacity-20 pointer-events-none">
            <i class="fa-brands fa-d-and-d text-[30rem]"></i>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12">
        <div class="w-full max-w-md">
            
            <div class="lg:hidden flex items-center space-x-3 mb-10 justify-center">
                <div class="w-10 h-10 bg-[#0b276b] rounded-full flex items-center justify-center text-white text-xl shadow-md">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                <span class="text-2xl font-black text-[#0b276b] tracking-wide">AlgoLearn</span>
            </div>

            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-slate-800 mb-2">Login Dulu Lek!</h2>
                <p class="text-slate-500 font-medium">Siapin mental buat ngerjain evaluasi hari ini.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Akademik</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-slate-400 text-lg"></i>
                        </div>
                        <input type="email" name="email" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-[#0b276b] focus:ring-4 focus:ring-blue-50 text-slate-800 font-medium transition-all text-lg" placeholder="nama@institusi.ac.id" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 text-lg"></i>
                        </div>
                        <input type="password" name="password" class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-[#0b276b] focus:ring-4 focus:ring-blue-50 text-slate-800 font-medium transition-all text-lg" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 pb-4">
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" name="remember" class="rounded-lg border-2 border-slate-300 text-[#0b276b] focus:ring-[#0b276b] w-5 h-5 transition-colors">
                        <span class="text-sm font-bold text-slate-500 group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-[#0b276b] hover:text-blue-800 transition-colors">Lupa sandi?</a>
                </div>

                <button type="submit" class="w-full bg-[#0b276b] hover:bg-blue-900 text-white font-black text-lg py-4 px-6 rounded-2xl uppercase tracking-wider transition-all shadow-[0_6px_0_0_#061a4f] hover:shadow-[0_2px_0_0_#061a4f] hover:translate-y-[4px] flex justify-center items-center space-x-2">
                    <span>Mulai Main</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="mt-10 text-center font-bold text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-emerald-500 hover:text-emerald-600 transition-colors ml-1">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection