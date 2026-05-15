@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50 pb-20">
    <header class="bg-white border-b border-slate-200 px-8 py-6 sticky top-0 z-50 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.tasks.index') }}" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-0.5">Arena Kuis</p>
                <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">{{ $quiz->title }}</h1>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto mt-10 px-4">
        <form action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            <div class="space-y-8">
                @foreach($quiz->questions as $index => $q)
                    @php
                        $parsedText = e($q->question_text);
                        $parsedText = nl2br($parsedText);
                        $parsedText = preg_replace_callback('/\[code\](.*?)\[\/code\]/is', function($matches) {
                            $cleanCode = str_replace('<br />', '', $matches[1]);
                            return '<pre class="bg-[#0f172a] text-emerald-400 p-5 rounded-2xl my-4 overflow-x-auto font-mono text-sm shadow-inner border-2 border-slate-800"><code>' . trim($cleanCode) . '</code></pre>';
                        }, $parsedText);
                    @endphp

                    <div class="bg-white p-8 rounded-3xl shadow-sm border-2 border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 text-lg leading-relaxed">
                            <span class="text-amber-500 mr-2">{{ $index + 1 }}.</span> {!! $parsedText !!}
                        </h3>

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
                                <div class="min-h-[100px] p-6 rounded-2xl border-2 border-dashed border-amber-300 bg-amber-50/50 mb-6 flex flex-wrap gap-3 items-center">
                                    <template x-if="selected.length === 0">
                                        <span class="text-slate-400 text-sm font-bold w-full text-center">Tap blok di bawah buat nyusun kode...</span>
                                    </template>
                                    <template x-for="(block, i) in selected" :key="i">
                                        <button @click="moveToAvailable(i)" type="button" class="px-5 py-3 bg-amber-500 text-white font-mono text-sm rounded-xl shadow-[0_4px_0_0_#b45309] active:translate-y-[4px] active:shadow-none transition-all">
                                            <span x-text="block"></span>
                                        </button>
                                    </template>
                                </div>
                                <div class="p-6 rounded-2xl bg-slate-100 flex flex-wrap gap-3 justify-center border-2 border-slate-200">
                                    <template x-for="(block, i) in available" :key="i">
                                        <button @click="moveToSelected(i)" type="button" class="px-5 py-3 bg-white text-slate-700 font-mono text-sm rounded-xl border-2 border-slate-200 shadow-[0_4px_0_0_#cbd5e1] active:translate-y-[4px] active:shadow-none transition-all">
                                            <span x-text="block"></span>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="answers[{{ $q->id }}]" :value="selected.join(' ')">
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(['a','b','c','d'] as $opt)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="hidden peer" required>
                                        <div class="p-5 rounded-2xl border-2 border-slate-100 peer-checked:border-amber-400 peer-checked:bg-amber-50 text-slate-600 peer-checked:text-amber-900 font-medium transition-all flex items-center shadow-sm">
                                            <span class="inline-flex w-10 h-10 bg-slate-100 text-slate-500 peer-checked:bg-amber-400 peer-checked:text-white rounded-xl items-center justify-center text-sm font-black mr-4 uppercase shrink-0 transition-colors">{{ $opt }}</span>
                                            <span class="text-sm font-bold">{{ $q->{'option_'.$opt} }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                <button type="submit" class="w-full py-5 bg-amber-500 text-slate-900 font-black text-lg uppercase tracking-widest rounded-3xl shadow-[0_6px_0_0_#b45309] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#b45309] active:translate-y-[6px] active:shadow-none transition-all">
                    Submit Jawaban
                </button>
            </div>
        </form>
    </main>
</div>
@endsection