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
                return '<div class="bg-[#1e1e1e] rounded-xl my-3 overflow-hidden shadow-sm border border-slate-800 w-full block text-left"><div class="bg-[#2d2d2d] px-4 py-2 flex items-center space-x-2 border-b border-black/50"><div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-slate-400 text-[10px] font-mono ml-4 uppercase tracking-widest hidden sm:inline-block">Code Snippet</span></div><div class="p-4 overflow-x-auto font-mono text-sm text-green-400 leading-relaxed whitespace-pre-wrap">' . trim($code) . '</div></div>';
            }, $safe);
            return $safe;
        }
    }
@endphp

<div class="min-h-screen bg-white flex flex-col font-sans" 
    x-data="{
        currentIndex: 0,
        totalQuestions: {{ count($questions) }},
        answers: {},
        timeLeft: 1800, // 30 Menit dalam detik (30 * 60)
        timerInterval: null,
        questionIds: [
            @foreach($questions as $q)
                {{ $q->id }},
            @endforeach
        ],
        get currentQId() { return this.questionIds[this.currentIndex]; },
        get progress() { return (this.currentIndex / this.totalQuestions) * 100; },
        
        get formattedTime() {
            let m = Math.floor(this.timeLeft / 60).toString().padStart(2, '0');
            let s = (this.timeLeft % 60).toString().padStart(2, '0');
            return m + ':' + s;
        },
        
        startTimer() {
            this.timerInterval = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    clearInterval(this.timerInterval);
                    // Waktu habis! Auto kumpul tanpa ampun
                    document.getElementById('pretestForm').submit();
                }
            }, 1000);
        },
        
        nextQuestion() {
            if(this.currentIndex < this.totalQuestions - 1) {
                this.currentIndex++;
            } else {
                document.getElementById('pretestForm').submit();
            }
        }
    }"
    x-init="startTimer()">

    <!-- Header & Progress Bar -->
    <header class="bg-white px-6 py-5 flex items-center sticky top-0 z-40 max-w-5xl mx-auto w-full border-b border-slate-100">
        <form action="{{ route('logout') }}" method="POST" class="mr-4">
            @csrf
            <button class="text-slate-400 hover:text-red-500 text-2xl transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </form>
        
        <div class="flex-grow bg-slate-200 h-4 rounded-full overflow-hidden relative mx-4">
            <div class="bg-[#0b276b] h-full rounded-full transition-all duration-500 ease-out absolute left-0 top-0" :style="'width: ' + progress + '%'">
                <div class="w-full h-1 bg-white/30 absolute top-1 rounded-full px-2 mx-1 scale-x-95"></div>
            </div>
        </div>
        
        <!-- Timer yang jalan beneran -->
        <div class="font-black shrink-0 flex items-center px-3 py-1.5 rounded-xl border transition-all duration-300"
             :class="timeLeft <= 60 ? 'bg-red-100 text-red-600 border-red-300 animate-pulse scale-110 shadow-lg shadow-red-200' : 'bg-amber-50 text-amber-500 border-amber-200'">
            <i class="fa-solid fa-hourglass-half mr-2"></i> 
            <span x-text="formattedTime"></span>
        </div>
    </header>

    <!-- Konten Soal -->
    <main class="flex-grow w-full max-w-3xl mx-auto py-8 px-6 flex flex-col justify-center relative">
        <form id="pretestForm" action="{{ route('student.pretest.store') }}" method="POST" class="w-full pb-24">
            @csrf
            
            @foreach($questions ?? [] as $index => $q)
            <div x-show="currentIndex === {{ $index }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;" class="w-full">
                
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-8 leading-snug">
                    {!! formatAlgoCode($q->question) !!}
                </h2>
                
                <div class="grid grid-cols-1 gap-4">
                    @foreach(['a', 'b', 'c', 'd'] as $option)
                    <label 
                        class="flex items-center p-4 md:p-5 border-2 rounded-2xl cursor-pointer transition-all duration-200 w-full select-none"
                        :class="{
                            'border-slate-200 hover:bg-slate-50 text-slate-700': answers[{{ $q->id }}] !== '{{ $option }}',
                            'border-emerald-400 bg-emerald-50 ring-4 ring-emerald-100 text-emerald-900 font-bold': answers[{{ $q->id }}] === '{{ $option }}'
                        }">
                        
                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $option }}" x-model="answers[{{ $q->id }}]" class="hidden" required>
                        
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm mr-4 border-2 transition-colors shrink-0"
                            :class="{
                                'border-slate-300 text-slate-400': answers[{{ $q->id }}] !== '{{ $option }}',
                                'border-emerald-500 text-emerald-600 bg-emerald-100': answers[{{ $q->id }}] === '{{ $option }}'
                            }">
                            {{ strtoupper($option) }}
                        </div>
                        
                        <div class="text-base md:text-lg w-full break-words">
                            {!! formatAlgoCode($q->{'option_'.$option}) !!}
                        </div>
                    </label>
                    @endforeach
                </div>

            </div>
            @endforeach
        </form>
    </main>

    <!-- Footer / Area Tombol Lanjut -->
    <footer class="fixed bottom-0 left-0 w-full bg-white border-t-2 border-slate-200 p-4 sm:p-6 z-40">
        <div class="max-w-3xl mx-auto flex justify-between items-center">
            
            <div class="hidden sm:block text-slate-500 font-bold">
                Soal <span x-text="currentIndex + 1"></span> dari {{ count($questions) }}
            </div>

            <!-- Tombol Lanjut -->
            <button type="button" @click="nextQuestion()" :disabled="!answers[currentQId]" 
                class="w-full sm:w-auto px-10 py-4 rounded-2xl font-black text-lg uppercase tracking-wider transition-all"
                :class="answers[currentQId] 
                    ? (currentIndex < totalQuestions - 1 
                        ? 'bg-[#0b276b] hover:bg-blue-900 text-white shadow-[0_6px_0_0_#061a4f] hover:shadow-[0_2px_0_0_#061a4f] hover:translate-y-[4px]' 
                        : 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-[0_6px_0_0_#059669] hover:shadow-[0_2px_0_0_#059669] hover:translate-y-[4px]') 
                    : 'bg-slate-200 text-slate-400 cursor-not-allowed'">
                <span x-text="currentIndex < totalQuestions - 1 ? 'Lanjut Lek!' : 'Kumpul & Cek Kasta'"></span>
            </button>

        </div>
    </footer>
</div>
@endsection