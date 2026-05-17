@extends('layouts.app')
@section('content')

@php
    $alpineQuizData = $quiz->questions->map(function($q) {
        return [
            'id' => $q->id,
            'type' => $q->type,
            'correct' => trim((string)$q->correct_option),
            'explanation' => preg_replace('/\s+/', ' ', $q->explanation ?? 'GM lu males ngasih penjelasan.')
        ];
    });
@endphp

@if(isset($askRepeat) && $askRepeat)
    <div class="fixed inset-0 z-[99999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm" x-data="{show: true}" x-show="show">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 relative shadow-2xl border-4 border-amber-400 text-center">
            <div class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fa-solid fa-rotate-right text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Ulangi Kuis?</h3>
            <p class="text-slate-500 font-bold mb-8 text-sm leading-relaxed">Lu udah pernah ngerjain kuis ini lek! Yakin mau ngulang? Keringat lu yang kemaren tetep kecatet sih, tapi menuuh-menuhin <i>database</i> GM doang.</p>
            
            <div class="flex gap-3">
                <a href="{{ route('student.quiz.show', ['quiz' => $quiz->id, 'confirm' => 1]) }}" class="flex-1 py-4 bg-amber-500 text-white font-black uppercase tracking-widest rounded-xl shadow-[0_4px_0_0_#d97706] hover:translate-y-[2px] hover:shadow-none transition-all text-xs flex items-center justify-center">
                    Gaskeun Ulang
                </a>
                <a href="{{ route('student.tasks.index') }}" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all text-xs flex items-center justify-center">
                    Balik Markas
                </a>
            </div>
        </div>
    </div>
@endif

<div class="h-screen flex flex-col bg-white overflow-hidden" x-data="quizApp">
    
    <header class="w-full px-6 py-8 flex items-center justify-between max-w-5xl mx-auto gap-6 shrink-0 z-50 bg-white">
        <button type="button" @click="triggerWarning('Lu udah masuk kandang macan lek! Kaga ada jalan keluar selain nyelesaiin kuis ini!')" class="text-slate-300 hover:text-red-500 transition-colors active:scale-90" title="Gembok Ujian">
            <i class="fa-solid fa-lock text-3xl"></i>
        </button>
        <div class="flex-1 h-5 bg-slate-200 rounded-full overflow-hidden shadow-inner">
            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500 ease-out relative" :style="'width: ' + progress() + '%'">
                <div class="absolute top-1 left-2 right-2 h-1.5 bg-white/30 rounded-full"></div>
            </div>
        </div>
        <div class="font-black text-emerald-500 text-lg w-12 text-center" x-text="(currentIndex + 1) + '/' + totalQuestions"></div>
    </header>

    <form id="penjara-kuis" action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST" class="flex-1 flex flex-col overflow-hidden relative">
        @csrf
        <div class="flex-1 overflow-y-auto px-4">
            <div class="max-w-3xl mx-auto w-full py-4 pb-64 relative">
                @foreach($quiz->questions as $index => $q)
                    @php
                        $parsedText = e($q->question_text);
                        $parsedText = nl2br($parsedText);
                        $parsedText = preg_replace_callback('/\[code\](.*?)\[\/code\]/is', function($matches) {
                            $cleanCode = str_replace('<br />', '', $matches[1]);
                            return '<div class="text-left"><pre class="bg-[#0f172a] text-emerald-400 p-6 rounded-2xl my-6 overflow-x-auto font-mono text-sm shadow-inner border-2 border-slate-800"><code>' . trim($cleanCode) . '</code></pre></div>';
                        }, $parsedText);
                    @endphp

                    <div x-show="currentIndex === {{ $index }}" 
                         style="display: none;"
                         x-transition:enter="transition-all ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-x-12" 
                         x-transition:enter-end="opacity-100 translate-x-0" 
                         class="w-full">
                        
                        <h2 class="font-black text-slate-800 mb-10 text-2xl md:text-3xl leading-relaxed">
                            {!! $parsedText !!}
                        </h2>

                        @if($q->type == 'arrange')
                            <div x-data="{
                                    available: @js(explode(',', $q->options)),
                                    selected: [],
                                    moveToSelected(i) {
                                        if(status[currentIndex]?.checked) return;
                                        this.selected.push(this.available[i]);
                                        this.available.splice(i, 1);
                                    },
                                    moveToAvailable(i) {
                                        if(status[currentIndex]?.checked) return;
                                        this.available.push(this.selected[i]);
                                        this.selected.splice(i, 1);
                                    }
                                 }">
                                 
                                <div class="min-h-[120px] py-6 border-t-2 border-b-2 border-slate-200 mb-10 flex flex-wrap gap-3 items-center">
                                    <template x-if="selected.length === 0">
                                        <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl h-14 flex items-center justify-center bg-slate-50 text-slate-400 font-bold text-sm">Susun balok jawaban lu di sini</div>
                                    </template>
                                    <template x-for="(block, i) in selected" :key="i">
                                        <button @click="moveToAvailable(i)" type="button" class="px-5 py-3 bg-white text-slate-700 font-mono text-base font-bold rounded-2xl border-2 border-slate-200 shadow-[0_4px_0_0_#cbd5e1] transition-all" :class="status[currentIndex]?.checked ? 'opacity-50 cursor-not-allowed' : 'active:translate-y-[4px] active:shadow-none'">
                                            <span x-text="block.trim()"></span>
                                        </button>
                                    </template>
                                </div>
                                
                                <div class="flex flex-wrap gap-3 justify-center">
                                    <template x-for="(block, i) in available" :key="i">
                                        <button @click="moveToSelected(i)" type="button" class="px-5 py-3 bg-white text-slate-700 font-mono text-base font-bold rounded-2xl border-2 border-slate-200 shadow-[0_4px_0_0_#cbd5e1] transition-all" :class="status[currentIndex]?.checked ? 'opacity-50 cursor-not-allowed' : 'active:translate-y-[4px] active:shadow-none'">
                                            <span x-text="block.trim()"></span>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="answers[{{ $q->id }}]" :value="selected.join(',')">
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(['a','b','c','d'] as $opt)
                                    <label class="cursor-pointer group block" :class="status[currentIndex]?.checked ? 'pointer-events-none opacity-80' : ''">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="hidden peer">
                                        <div class="p-6 rounded-2xl border-2 border-slate-200 peer-checked:border-sky-400 peer-checked:bg-sky-50 text-slate-600 peer-checked:text-sky-900 font-bold transition-all shadow-[0_4px_0_0_#e2e8f0] peer-checked:shadow-[0_4px_0_0_#38bdf8] flex items-center bg-white" :class="status[currentIndex]?.checked ? '' : 'active:translate-y-[4px] active:shadow-none'">
                                            <span class="inline-flex w-10 h-10 border-2 border-slate-200 text-slate-400 peer-checked:border-sky-400 peer-checked:text-sky-500 rounded-xl items-center justify-center text-sm font-black mr-4 uppercase shrink-0 transition-colors">{{ $opt }}</span>
                                            <span class="text-lg">{{ $q->{'option_'.$opt} }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="fixed bottom-0 left-0 w-full z-40 transition-colors duration-300"
             :class="status[currentIndex]?.checked ? (status[currentIndex]?.isRight ? 'bg-emerald-100 border-t-2 border-emerald-200' : 'bg-red-100 border-t-2 border-red-200') : 'bg-white border-t-2 border-slate-200'">
            
            <div x-show="status[currentIndex]?.checked" 
                 x-transition:enter="transition-all ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-8" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="max-w-4xl mx-auto px-6 pt-8 pb-2 flex gap-5 items-start" style="display: none;">
                
                <div class="w-14 h-14 rounded-full flex items-center justify-center shrink-0 text-3xl shadow-sm bg-white"
                     :class="status[currentIndex]?.isRight ? 'text-emerald-500' : 'text-red-500'">
                     <i class="fa-solid" :class="status[currentIndex]?.isRight ? 'fa-check' : 'fa-xmark'"></i>
                </div>
                
                <div class="flex-1">
                    <h4 class="font-black text-2xl tracking-tight mb-2" 
                        :class="status[currentIndex]?.isRight ? 'text-emerald-700' : 'text-red-700'" 
                        x-text="status[currentIndex]?.isRight ? 'Mantap! Bener Lek!' : 'Wasted! Salah lu.'"></h4>
                    
                    <div x-show="!status[currentIndex]?.isRight">
                        <p class="text-xs font-black uppercase tracking-widest mb-1 opacity-80 text-red-800">Jawaban yang bener:</p>
                        <p class="text-lg font-black text-red-900 mb-3 uppercase" x-text="quizData[currentIndex].correct"></p>
                        
                        <p class="text-xs font-black uppercase tracking-widest mb-1 opacity-80 text-red-800">Penjelasan GM:</p>
                        <p class="text-sm font-bold leading-relaxed text-red-900" x-text="quizData[currentIndex].explanation"></p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6 sm:py-8">
                <div class="max-w-4xl mx-auto flex justify-end items-center">
                    <button type="button" 
                            x-show="!status[currentIndex]?.checked" 
                            @click="checkAnswer()" 
                            class="px-10 sm:px-12 py-4 bg-sky-500 text-white font-black text-lg sm:text-xl uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#0284c7] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#0284c7] active:translate-y-[6px] active:shadow-none transition-all w-full md:w-auto text-center">
                        Cek Jawaban
                    </button>

                    <button type="button" 
                            x-show="status[currentIndex]?.checked && currentIndex < totalQuestions - 1" 
                            @click="currentIndex++" 
                            class="px-10 sm:px-12 py-4 text-white font-black text-lg sm:text-xl uppercase tracking-widest rounded-2xl transition-all w-full md:w-auto text-center"
                            :class="status[currentIndex]?.isRight ? 'bg-emerald-500 shadow-[0_6px_0_0_#059669] hover:shadow-[0_4px_0_0_#059669] active:translate-y-[6px] active:shadow-none' : 'bg-red-500 shadow-[0_6px_0_0_#b91c1c] hover:shadow-[0_4px_0_0_#b91c1c] active:translate-y-[6px] active:shadow-none'">
                        Lanjut
                    </button>

                    <button type="submit" onclick="document.getElementById('penjara-kuis').classList.add('bebas-murni')"
                            x-show="status[currentIndex]?.checked && currentIndex === totalQuestions - 1" 
                            class="px-10 sm:px-12 py-4 text-white font-black text-lg sm:text-xl uppercase tracking-widest rounded-2xl transition-all w-full md:w-auto text-center flex items-center justify-center gap-3"
                            :class="status[currentIndex]?.isRight ? 'bg-emerald-500 shadow-[0_6px_0_0_#059669] hover:shadow-[0_4px_0_0_#059669] active:translate-y-[6px] active:shadow-none' : 'bg-red-500 shadow-[0_6px_0_0_#b91c1c] hover:shadow-[0_4px_0_0_#b91c1c] active:translate-y-[6px] active:shadow-none'">
                        <i class="fa-solid fa-paper-plane"></i> Selesai
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div x-show="showWarningModal" 
         style="display: none;"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-3xl max-w-sm w-full p-8 relative shadow-2xl border-4 border-amber-400 text-center" 
             @click.away="showWarningModal = false"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Peringatan!</h3>
            <p class="text-slate-500 font-bold mb-8 text-sm leading-relaxed" x-text="warningMessage"></p>
            
            <button type="button" @click="showWarningModal = false" class="w-full py-4 bg-amber-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_4px_0_0_#d97706] hover:translate-y-[2px] hover:shadow-none transition-all">
                Oke Paham!
            </button>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('quizApp', () => ({
            currentIndex: 0,
            totalQuestions: {{ $quiz->questions->count() }},
            status: {},
            quizData: @json($alpineQuizData),
            showWarningModal: false,
            warningMessage: '',

            progress() {
                return this.totalQuestions > 0 ? ((this.currentIndex + 1) / this.totalQuestions) * 100 : 0;
            },

            triggerWarning(msg) {
                this.warningMessage = msg;
                this.showWarningModal = true;
            },

            checkAnswer() {
                let q = this.quizData[this.currentIndex];
                let userAns = '';
                
                if(q.type === 'multiple_choice') {
                    let selected = document.querySelector('input[name="answers[' + q.id + ']"]:checked');
                    if(selected) userAns = selected.value;
                } else {
                    let input = document.querySelector('input[name="answers[' + q.id + ']"]');
                    if(input) userAns = input.value;
                }

                if(!userAns) {
                    this.triggerWarning('Pilih jawaban atau susun baloknya dulu lek! Kaga bisa asal nge-skip!');
                    return;
                }

                let isRight = false;
                if (q.type === 'arrange') {
                    // Sapu bersih semua spasi biar kebal dari Dosen yang typo spasi
                    let cleanUserAns = userAns.replace(/\s+/g, '').toLowerCase();
                    let cleanCorrect = q.correct.replace(/\s+/g, '').toLowerCase();
                    isRight = (cleanUserAns === cleanCorrect);
                } else {
                    isRight = (userAns.toLowerCase() === q.correct.toLowerCase());
                }

                this.status[this.currentIndex] = { checked: true, isRight: isRight };
            }
        }));
    });

    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, location.href);
        document.querySelector('[x-data="quizApp"]').__x.$data.triggerWarning('Maju terus pantang mundur! Kaga bisa balik lek, beresin kuis lu!');
    });

    window.addEventListener('beforeunload', function (e) {
        if (!document.getElementById('penjara-kuis').classList.contains('bebas-murni')) {
            e.preventDefault();
            e.returnValue = 'Yakin lu mau kabur? Progress ujian lu bakal hangus mutlak!';
        }
    });
</script>
@endsection