@extends('layouts.app')
@section('content')

@php
    if (!function_exists('formatAlgoCode')) {
        function formatAlgoCode($text) {
            if (!$text) return '';
            $safe = htmlspecialchars($text);
            $safe = nl2br($safe);
            $safe = preg_replace_callback('/\[CODE\](.*?)\[\/CODE\]/is', function($matches) {
                $code = str_replace(['<br />', '<br>'], '', $matches[1]);
                return '<div class="bg-[#1e1e1e] rounded-xl my-3 overflow-hidden shadow-sm border border-slate-800 w-full block"><div class="bg-[#2d2d2d] px-4 py-2 flex items-center space-x-2 border-b border-black/50"><div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-slate-400 text-[10px] font-mono ml-4 uppercase tracking-widest hidden sm:inline-block">Code Snippet</span></div><div class="p-4 overflow-x-auto font-mono text-sm text-green-400 leading-relaxed whitespace-pre-wrap">' . trim($code) . '</div></div>';
            }, $safe);
            return $safe;
        }
    }
    
    $isPassed = $score >= $quiz->passing_grade;
@endphp

<div class="min-h-screen bg-slate-50 flex flex-col">
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center sticky top-0 z-40 shadow-sm">
        <a href="{{ route('student.tasks.index') }}" class="mr-6 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#0b276b] hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <p class="text-xs font-bold text-[#0b276b] uppercase tracking-wider mb-1">Hasil Evaluasi</p>
            <h1 class="text-xl font-bold text-slate-800">{{ $quiz->title }}</h1>
        </div>
    </header>

    <main class="flex-grow w-full max-w-4xl mx-auto py-10 px-4">
        
        <!-- Papan Skor Raksasa -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-10 mb-10 flex flex-col items-center text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 {{ $isPassed ? 'bg-[url(https://www.transparenttextures.com/patterns/stardust.png)]' : 'bg-[url(https://www.transparenttextures.com/patterns/diagmonds-light.png)]' }}"></div>
            
            <h2 class="text-2xl font-bold text-slate-600 mb-6 relative z-10">Total Skor Kamu</h2>
            
            <div class="w-48 h-48 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg border-8 {{ $isPassed ? 'border-emerald-100 bg-emerald-500' : 'border-red-100 bg-red-500' }}">
                <span class="text-6xl font-black text-white">{{ $score }}</span>
            </div>
            
            <h3 class="text-3xl font-extrabold mb-2 relative z-10 {{ $isPassed ? 'text-emerald-600' : 'text-red-600' }}">
                {{ $isPassed ? 'Luar Biasa, Lulus KKM!' : 'Remedial Woy, Belajar Lagi!' }}
            </h3>
            <p class="text-slate-500 relative z-10">Batas Minimal Kelulusan (KKM): <b>{{ $quiz->passing_grade }}</b></p>
        </div>

        <h3 class="font-bold text-xl text-slate-800 mb-6 px-2">Review Jawabanmu</h3>

        <div class="space-y-6">
            @foreach($quiz->questions as $index => $question)
            @php
                $userAnswer = $answers[$question->id] ?? null;
                $isCorrect = $userAnswer === $question->correct_option;
            @endphp
            <div class="bg-white rounded-xl border-l-8 shadow-sm p-6 {{ $isCorrect ? 'border-emerald-500' : 'border-red-500' }}">
                
                <div class="flex items-start justify-between mb-4">
                    <h4 class="font-bold text-slate-800 text-lg flex items-start space-x-3">
                        <span class="text-slate-400 mt-1">#{{ $index + 1 }}</span>
                        <div class="w-full text-slate-700 font-medium">
                            {!! formatAlgoCode($question->question_text) !!}
                        </div>
                    </h4>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $isCorrect ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                        <i class="fa-solid {{ $isCorrect ? 'fa-check' : 'fa-xmark' }}"></i>
                    </div>
                </div>

                <div class="pl-10 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $option)
                    @php
                        $isThisCorrectOption = $key === $question->correct_option;
                        $isThisUserChoice = $key === $userAnswer;
                        
                        $bgClass = 'bg-slate-50 border-slate-200 text-slate-500';
                        if ($isThisCorrectOption) $bgClass = 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold';
                        elseif ($isThisUserChoice && !$isCorrect) $bgClass = 'bg-red-50 border-red-400 text-red-800 line-through opacity-70';
                    @endphp
                    <div class="flex items-start p-3 border rounded-lg {{ $bgClass }}">
                        <span class="uppercase font-extrabold mr-3 shrink-0 {{ $isThisCorrectOption ? 'text-emerald-500' : 'text-slate-400' }}">{{ $key }}.</span>
                        <div class="break-words w-full">{!! formatAlgoCode($option) !!}</div>
                        
                        @if($isThisUserChoice && !$isCorrect)
                            <i class="fa-solid fa-xmark text-red-500 ml-2 mt-1 shrink-0"></i>
                        @elseif($isThisCorrectOption)
                            <i class="fa-solid fa-check text-emerald-500 ml-2 mt-1 shrink-0"></i>
                        @endif
                    </div>
                    @endforeach
                </div>

            </div>
            @endforeach
        </div>
        
        <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ route('student.tasks.index') }}" class="inline-block bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-4 px-10 rounded-2xl transition-all border border-slate-200">
                Kembali ke Daftar Tugas
            </a>
            
            @if(!$isPassed)
            <a href="{{ route('student.quiz.show', $quiz->id) }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-10 rounded-2xl transition-all shadow-[0_4px_0_0_#b91c1c] hover:translate-y-[2px] hover:shadow-[0_2px_0_0_#b91c1c]">
                <i class="fa-solid fa-fire mr-2"></i> Remedial Sekarang!
            </a>
            @endif
        </div>

    </main>
</div>
@endsection