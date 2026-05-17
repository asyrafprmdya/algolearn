@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

<div class="min-h-screen bg-slate-50 relative overflow-hidden pb-32" 
     x-data="{ 
        showLevelModal: {{ session()->has('level_up') ? 'true' : 'false' }},
        triggerConfetti() {
            let duration = 3 * 1000;
            let end = Date.now() + duration;
            let frame = () => {
                confetti({ particleCount: 5, angle: 60, spread: 55, origin: { x: 0 }, colors: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'] });
                confetti({ particleCount: 5, angle: 120, spread: 55, origin: { x: 1 }, colors: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'] });
                if (Date.now() < end) requestAnimationFrame(frame);
            };
            frame();
        }
     }"
     x-init="if(showLevelModal) setTimeout(() => triggerConfetti(), 400)">
    
    <div class="max-w-3xl w-full mx-auto text-center pt-20 px-6 animate-fade-in-up">
        @if($result->is_passed)
            <div class="w-32 h-32 bg-emerald-100 text-emerald-500 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-xl rotate-6">
                <i class="fa-solid fa-face-grin-stars text-6xl -rotate-6"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter mb-4">Misi Berhasil!</h1>
            <p class="text-slate-500 font-bold text-lg leading-relaxed">Goks! Lu berhasil lolos dari maut dengan skor di atas KKM lek.</p>
        @else
            <div class="w-32 h-32 bg-red-100 text-red-500 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-xl -rotate-6">
                <i class="fa-solid fa-skull-crossbones text-6xl rotate-6"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter mb-4">Wasted! Gagal Total.</h1>
            <p class="text-slate-500 font-bold text-lg leading-relaxed">Skor lu mengenaskan lek. KKM-nya {{ $quiz->passing_grade }}, lu malah dapet segitu. Remedial gih!</p>
        @endif

        <div class="my-12 p-10 rounded-[3rem] bg-white border-4 {{ $result->is_passed ? 'border-emerald-100' : 'border-red-100' }} shadow-sm relative group hover:scale-105 transition-transform duration-300">
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-6 py-1 bg-white border-2 {{ $result->is_passed ? 'border-emerald-200 text-emerald-500' : 'border-red-200 text-red-500' }} rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                Rapor Sementara
            </div>
            <div class="text-7xl font-black {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }} tracking-tighter">
                {{ $result->score }}<span class="text-2xl opacity-40 ml-1">/100</span>
            </div>
        </div>

        <div class="space-y-4 max-w-xs mx-auto mb-20">
            @if($result->is_passed)
                <a href="{{ route('student.tasks.index') }}" class="block w-full py-5 bg-emerald-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#059669] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#059669] active:translate-y-[6px] active:shadow-none transition-all text-sm">
                    Lanjut Cari Perkara
                </a>
            @else
                <a href="{{ route('student.quiz.show', $quiz->id) }}" class="block w-full py-5 bg-red-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#b91c1c] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#b91c1c] active:translate-y-[6px] active:shadow-none transition-all text-sm animate-bounce">
                    <i class="fa-solid fa-rotate-right mr-2"></i> Jalur Remedial
                </a>
                <a href="{{ route('student.tasks.index') }}" class="block w-full py-4 text-slate-400 hover:text-slate-600 font-black uppercase tracking-widest text-[10px] transition-colors">
                    Nyerah Dulu, Balik ke Markas
                </a>
            @endif
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 animate-fade-in-up" style="animation-delay: 0.2s">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-8 text-center flex items-center justify-center gap-3">
            <i class="fa-solid fa-book-open-reader text-indigo-500"></i> Review Dosa Lu
        </h3>
        <div class="space-y-6">
            @foreach($quiz->questions as $index => $q)
                <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-slate-200 shadow-sm text-left relative overflow-hidden group hover:border-indigo-300 transition-colors">
                    <div class="absolute top-0 right-0 bg-slate-100 text-slate-400 font-black text-xs px-4 py-2 rounded-bl-2xl">#{{ $index + 1 }}</div>
                    <h4 class="font-bold text-slate-700 text-lg mb-6 pr-12 leading-relaxed">
                        {!! strip_tags(preg_replace('/\[code\].*?\[\/code\]/is', '[Kode]', $q->question_text)) !!}
                    </h4>
                    <div class="bg-indigo-50/50 border-2 border-indigo-100 rounded-2xl p-6 relative">
                        <div class="absolute -top-3 left-6 px-3 py-0.5 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">Biar Lu Paham</div>
                        <p class="text-indigo-900 font-medium text-sm leading-relaxed mt-2">{{ $q->explanation ?? 'GM kaga ngasih penjelasan, coba tanya Google aja.' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if(session()->has('level_up'))
    <div x-show="showLevelModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-md" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-[3.5rem] w-full max-w-sm p-12 text-center relative shadow-2xl border-b-[12px] border-amber-500" @click.outside="showLevelModal = false" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="absolute inset-0 overflow-hidden rounded-[3rem] pointer-events-none opacity-10">
                <i class="fa-solid fa-crown text-[15rem] absolute -right-10 -top-10 text-amber-500 animate-pulse"></i>
            </div>
            <div class="relative z-10">
                <div class="w-28 h-28 bg-gradient-to-tr from-amber-400 to-yellow-300 text-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg animate-bounce border-4 border-white">
                    <i class="fa-solid fa-rocket text-5xl"></i>
                </div>
                <h2 class="text-4xl font-black text-slate-800 uppercase tracking-tighter mb-2">Level Up!</h2>
                <p class="text-slate-500 font-bold mb-8 leading-relaxed text-sm">Kasta lu resmi naik ke <span class="text-amber-600 font-black">{{ session('level_up')['new'] }}</span> lek!</p>
                <button @click="showLevelModal = false" class="w-full py-5 bg-amber-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#b45309] hover:translate-y-[2px] transition-all text-sm">Gaskeun!</button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection