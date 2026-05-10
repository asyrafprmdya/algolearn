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
        totalQuestions: {{ count($quiz->questions) }},
        answers: {},
        isAnswered: false,
        isCorrect: false,
        correctAnswers: {
            @foreach($quiz->questions as $q)
                {{ $q->id }}: '{{ $q->correct_option }}',
            @endforeach
        },
        questionIds: [
            @foreach($quiz->questions as $q)
                {{ $q->id }},
            @endforeach
        ],
        get currentQId() { return this.questionIds[this.currentIndex]; },
        get progress() { return ((this.currentIndex) / this.totalQuestions) * 100; },
        
        checkAnswer() {
            if(!this.answers[this.currentQId]) return;
            this.isAnswered = true;
            this.isCorrect = this.answers[this.currentQId] === this.correctAnswers[this.currentQId];
        },
        nextQuestion() {
            if(this.currentIndex < this.totalQuestions - 1) {
                this.currentIndex++;
                this.isAnswered = false;
                this.isCorrect = false;
            } else {
                document.getElementById('quizForm').submit();
            }
        }
    }">

    <header class="bg-white px-6 py-5 flex items-center sticky top-0 z-40 max-w-5xl mx-auto w-full">
        <a href="{{ route('student.tasks.index') }}" class="mr-4 text-slate-400 hover:text-slate-700 text-2xl transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </a>
        <div class="flex-grow bg-slate-200 h-4 rounded-full overflow-hidden relative">
            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500 ease-out absolute left-0 top-0" :style="'width: ' + progress + '%'">
                <div class="w-full h-1 bg-white/30 absolute top-1 rounded-full px-2 mx-1 scale-x-95"></div>
            </div>
        </div>
        <div class="ml-4 font-bold text-slate-500 shrink-0">
            <i class="fa-solid fa-heart text-red-500"></i> Fokus!
        </div>
    </header>

    <main class="flex-grow w-full max-w-3xl mx-auto py-4 px-6 flex flex-col justify-center">
        <form id="quizForm" action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST" class="w-full">
            @csrf
            
            @foreach($quiz->questions as $index => $question)
            <div x-show="currentIndex === {{ $index }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;" class="w-full">
                
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-8 leading-snug">
                    {!! formatAlgoCode($question->question_text) !!}
                </h2>
                
                <div class="grid grid-cols-1 gap-4">
                    @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $option)
                    <!-- Perhatiin Class label ini, 'pointer-events-none' bikin nggak bisa diklik kalau udah dijawab -->
                    <label 
                        class="flex items-center p-4 md:p-5 border-2 rounded-2xl transition-all duration-200 w-full select-none"
                        :class="{
                            'cursor-pointer border-slate-200 hover:bg-slate-50': answers[{{ $question->id }}] !== '{{ $key }}' && !isAnswered,
                            'cursor-pointer border-blue-400 bg-blue-50 ring-4 ring-blue-100': answers[{{ $question->id }}] === '{{ $key }}' && !isAnswered,
                            
                            'pointer-events-none border-emerald-500 bg-emerald-50': isAnswered && '{{ $key }}' === correctAnswers[{{ $question->id }}],
                            'pointer-events-none border-red-400 bg-red-50': isAnswered && answers[{{ $question->id }}] === '{{ $key }}' && '{{ $key }}' !== correctAnswers[{{ $question->id }}],
                            'pointer-events-none opacity-50 border-slate-200': isAnswered && answers[{{ $question->id }}] !== '{{ $key }}' && '{{ $key }}' !== correctAnswers[{{ $question->id }}]
                        }">
                        
                        <!-- :disabled="isAnswered" udah GUE CABUT dari sini -->
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" x-model="answers[{{ $question->id }}]" class="hidden">
                        
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm mr-4 border-2 transition-colors shrink-0"
                            :class="{
                                'border-slate-300 text-slate-400': answers[{{ $question->id }}] !== '{{ $key }}' && !isAnswered,
                                'border-blue-500 text-blue-600 bg-blue-100': answers[{{ $question->id }}] === '{{ $key }}' && !isAnswered,
                                'border-emerald-500 text-emerald-600 bg-emerald-100': isAnswered && '{{ $key }}' === correctAnswers[{{ $question->id }}],
                                'border-red-500 text-red-600 bg-red-100': isAnswered && answers[{{ $question->id }}] === '{{ $key }}' && '{{ $key }}' !== correctAnswers[{{ $question->id }}]
                            }">
                            {{ strtoupper($key) }}
                        </div>
                        
                        <div class="text-base md:text-lg font-medium w-full break-words" :class="{'text-slate-700': !isAnswered, 'text-emerald-800 font-bold': isAnswered && '{{ $key }}' === correctAnswers[{{ $question->id }}], 'text-red-800': isAnswered && answers[{{ $question->id }}] === '{{ $key }}' && '{{ $key }}' !== correctAnswers[{{ $question->id }}]}">
                            {!! formatAlgoCode($option) !!}
                        </div>
                    </label>
                    @endforeach
                </div>
                
                <div x-show="isAnswered && !isCorrect" x-transition class="mt-6">
                    <p class="font-bold text-red-600 text-sm mb-1">Jawaban yang benar adalah:</p>
                    <div class="p-4 bg-emerald-50 border-2 border-emerald-500 rounded-xl font-medium text-emerald-800">
                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $k => $opt)
                            <div x-show="correctAnswers[{{ $question->id }}] === '{{ $k }}'">{!! formatAlgoCode($opt) !!}</div>
                        @endforeach
                    </div>
                </div>

            </div>
            @endforeach
        </form>
    </main>

    <footer class="border-t-2 transition-colors duration-300" 
        :class="{
            'border-slate-200 bg-white': !isAnswered,
            'border-emerald-200 bg-emerald-100': isAnswered && isCorrect,
            'border-red-200 bg-red-100': isAnswered && !isCorrect
        }">
        <div class="max-w-5xl mx-auto px-6 py-6 flex items-center justify-between">
            
            <div class="flex items-center space-x-4">
                <div x-show="isAnswered && isCorrect" style="display: none;" class="flex flex-col">
                    <div class="flex items-center space-x-3 text-emerald-600">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-xl shadow-sm"><i class="fa-solid fa-check"></i></div>
                        <h3 class="text-2xl font-extrabold">Mantap Jiwa!</h3>
                    </div>
                </div>
                
                <div x-show="isAnswered && !isCorrect" style="display: none;" class="flex flex-col">
                    <div class="flex items-center space-x-3 text-red-500">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-xl shadow-sm"><i class="fa-solid fa-xmark"></i></div>
                        <h3 class="text-2xl font-extrabold">Kurang Tepat, Lek!</h3>
                    </div>
                </div>
            </div>

            <button type="button" x-show="!isAnswered" @click="checkAnswer()" :disabled="!answers[currentQId]" 
                class="px-8 py-3.5 rounded-2xl font-extrabold text-lg uppercase tracking-wide transition-all"
                :class="answers[currentQId] ? 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-[0_4px_0_0_#059669] hover:shadow-[0_2px_0_0_#059669] hover:translate-y-[2px]' : 'bg-slate-200 text-slate-400 cursor-not-allowed'">
                Cek Jawaban
            </button>

            <button type="button" x-show="isAnswered" @click="nextQuestion()" style="display: none;"
                class="px-8 py-3.5 rounded-2xl font-extrabold text-lg uppercase tracking-wide transition-all shadow-[0_4px_0_0_rgba(0,0,0,0.2)] hover:shadow-[0_2px_0_0_rgba(0,0,0,0.2)] hover:translate-y-[2px]"
                :class="isCorrect ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-red-500 hover:bg-red-600 text-white'">
                <span x-text="currentIndex < totalQuestions - 1 ? 'Lanjut' : 'Lihat Hasil'"></span>
            </button>

        </div>
    </footer>
</div>
@endsection