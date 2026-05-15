@extends('layouts.app')
@section('content')
<div class="h-screen flex flex-col bg-white overflow-hidden" x-data="{
        currentIndex: 0,
        totalQuestions: {{ $quiz->questions->count() }},
        progress() {
            return ((this.currentIndex + 1) / this.totalQuestions) * 100;
        }
    }">
    
    <header class="w-full px-6 py-8 flex items-center justify-between max-w-5xl mx-auto gap-6 shrink-0 z-50 bg-white">
        <a href="{{ route('student.tasks.index') }}" class="text-slate-300 hover:text-slate-500 transition-colors active:scale-90">
            <i class="fa-solid fa-xmark text-3xl"></i>
        </a>
        <div class="flex-1 h-5 bg-slate-200 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500 ease-out relative" :style="'width: ' + progress() + '%'">
                <div class="absolute top-1 left-2 right-2 h-1.5 bg-white/30 rounded-full"></div>
            </div>
        </div>
        <div class="font-black text-emerald-500 text-lg w-12 text-center" x-text="(currentIndex + 1) + '/' + totalQuestions"></div>
    </header>

    <form action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST" class="flex-1 flex flex-col overflow-hidden relative">
        @csrf
        <div class="flex-1 overflow-y-auto px-4">
            <div class="max-w-3xl mx-auto w-full py-4 pb-40 relative">
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
                         class="w-full w-full">
                        
                        <h2 class="font-black text-slate-800 mb-10 text-2xl md:text-3xl leading-relaxed">
                            {!! $parsedText !!}
                        </h2>

                        @if($q->type == 'arrange')
                            <div x-data="{
                                    available: @js(explode(',', $q->options)),
                                    selected: [],
                                    moveToSelected(i) {
                                        this.selected.push(this.available[i]);
                                        this.available.splice(i, 1);
                                    },
                                    moveToAvailable(i) {
                                        this.available.push(this.selected[i]);
                                        this.selected.splice(i, 1);
                                    }
                                 }">
                                 
                                <div class="min-h-[120px] py-6 border-t-2 border-b-2 border-slate-200 mb-10 flex flex-wrap gap-3 items-center">
                                    <template x-if="selected.length === 0">
                                        <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl h-14 flex items-center justify-center bg-slate-50"></div>
                                    </template>
                                    <template x-for="(block, i) in selected" :key="i">
                                        <button @click="moveToAvailable(i)" type="button" class="px-5 py-3 bg-white text-slate-700 font-mono text-base font-bold rounded-2xl border-2 border-slate-200 shadow-[0_4px_0_0_#cbd5e1] active:translate-y-[4px] active:shadow-none transition-all">
                                            <span x-text="block"></span>
                                        </button>
                                    </template>
                                </div>
                                
                                <div class="flex flex-wrap gap-3 justify-center">
                                    <template x-for="(block, i) in available" :key="i">
                                        <button @click="moveToSelected(i)" type="button" class="px-5 py-3 bg-white text-slate-700 font-mono text-base font-bold rounded-2xl border-2 border-slate-200 shadow-[0_4px_0_0_#cbd5e1] active:translate-y-[4px] active:shadow-none transition-all">
                                            <span x-text="block"></span>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="answers[{{ $q->id }}]" :value="selected.join(' ')">
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(['a','b','c','d'] as $opt)
                                    <label class="cursor-pointer group block">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="hidden peer">
                                        <div class="p-6 rounded-2xl border-2 border-slate-200 peer-checked:border-sky-400 peer-checked:bg-sky-50 text-slate-600 peer-checked:text-sky-900 font-bold transition-all shadow-[0_4px_0_0_#e2e8f0] peer-checked:shadow-[0_4px_0_0_#38bdf8] active:translate-y-[4px] active:shadow-none flex items-center bg-white">
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

        <div class="fixed bottom-0 left-0 w-full bg-white border-t-2 border-slate-200 px-6 py-8 z-50">
            <div class="max-w-5xl mx-auto flex justify-between items-center">
                <button type="button" x-show="currentIndex > 0" @click="currentIndex--" class="px-8 py-4 rounded-2xl font-black text-slate-400 uppercase tracking-widest hover:bg-slate-100 active:scale-95 transition-all" style="display: none;">
                    Kembali
                </button>
                <div x-show="currentIndex === 0" class="w-24"></div>

                <button type="button" x-show="currentIndex < totalQuestions - 1" @click="currentIndex++" class="px-12 py-4 bg-emerald-500 text-white font-black text-xl uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#059669] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#059669] active:translate-y-[6px] active:shadow-none transition-all w-full md:w-auto text-center">
                    Lanjut
                </button>

                <button type="submit" x-show="currentIndex === totalQuestions - 1" style="display: none;" class="px-12 py-4 bg-amber-500 text-white font-black text-xl uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#b45309] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#b45309] active:translate-y-[6px] active:shadow-none transition-all w-full md:w-auto text-center">
                    Cek Hasil
                </button>
            </div>
        </div>
    </form>
</div>
@endsection