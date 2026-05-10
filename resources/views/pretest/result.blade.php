@extends('layouts.app')
@section('content')

@php
    // Kita tangkep nilainya dari Session (kalau lemparannya pakai ->with('score', ...)) 
    // Atau dari variabel langsung (kalau lemparannya pakai compact('score'))
    $finalScore = session('score') ?? $score ?? 0;
@endphp

<div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center p-4 font-sans relative overflow-hidden">
    
    <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]"></div>
    <div class="absolute top-10 left-10 text-emerald-300 text-6xl rotate-12"><i class="fa-solid fa-star"></i></div>
    <div class="absolute bottom-20 right-10 text-blue-300 text-6xl -rotate-12"><i class="fa-solid fa-medal"></i></div>
    
    <div class="w-full max-w-2xl bg-white rounded-[2.5rem] border-2 border-slate-200 shadow-xl p-8 sm:p-14 relative z-10 text-center animate-fade-in-up">
        
        <div class="w-24 h-24 bg-[#0b276b] rounded-full flex items-center justify-center text-white text-4xl mx-auto mb-6 shadow-[0_6px_0_0_#061a4f]">
            <i class="fa-solid fa-ranking-star"></i>
        </div>

        <h1 class="text-4xl font-black text-slate-800 mb-2">Penentuan Kasta Selesai!</h1>
        <p class="text-lg font-medium text-slate-500 mb-10">Berdasarkan hasil uji nyali barusan, ini adalah skor dan level lu sekarang.</p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mb-12">
            <div class="w-full sm:w-1/2 bg-blue-50 border-2 border-blue-200 rounded-3xl p-6 relative">
                <p class="text-sm font-bold text-blue-400 uppercase tracking-widest mb-2">Skor Pretest</p>
                <!-- INI YANG BIKIN ANGKA 0 ILANG DAN BERUBAH JADI SKOR ASLI -->
                <div class="text-6xl font-black text-[#0b276b]">{{ $finalScore }}</div>
            </div>

            <div class="w-full sm:w-1/2 bg-emerald-50 border-2 border-emerald-200 rounded-3xl p-6 relative">
                <p class="text-sm font-bold text-emerald-400 uppercase tracking-widest mb-2">Kasta Lu Sekarang</p>
                <div class="text-3xl font-black text-emerald-600 uppercase flex items-center justify-center gap-2 mt-3 text-center leading-tight">
                    <i class="fa-solid fa-medal text-emerald-500"></i>
                    {{ Auth::user()->getLevel() }}
                </div>
            </div>
        </div>

        <div class="bg-slate-50 rounded-2xl p-6 border-2 border-slate-100 mb-10 text-left">
            <h3 class="font-bold text-slate-700 mb-2"><i class="fa-solid fa-bullhorn text-amber-500 mr-2"></i> Info Penting!</h3>
            <p class="text-slate-500 font-medium text-sm leading-relaxed">
                Level lu menentukan materi mana yang bisa lu buka duluan. Selesaikan kuis-kuis di level lu saat ini buat naik ke kasta selanjutnya. Jangan kasih kendor!
            </p>
        </div>

        <a href="{{ route('student.dashboard') }}" class="inline-flex w-full sm:w-auto bg-[#0b276b] hover:bg-blue-900 text-white font-black text-xl py-5 px-12 rounded-2xl uppercase tracking-wider transition-all shadow-[0_6px_0_0_#061a4f] hover:shadow-[0_2px_0_0_#061a4f] hover:translate-y-[4px] justify-center items-center space-x-3">
            <span>Mulai Petualangan</span>
            <i class="fa-solid fa-rocket"></i>
        </a>
        
    </div>
</div>
@endsection