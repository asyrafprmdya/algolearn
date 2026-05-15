@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

<div class="min-h-screen bg-white flex items-center justify-center p-6 relative overflow-hidden" 
     x-data="{ 
        showLevelModal: {{ session()->has('level_up') ? 'true' : 'false' }},
        triggerConfetti() {
            let duration = 3 * 1000;
            let end = Date.now() + duration;
            let frame = () => {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444']
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444']
                });
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            };
            frame();
        }
     }"
     x-init="if(showLevelModal) setTimeout(() => triggerConfetti(), 400)">
    
    <div class="max-w-md w-full text-center animate-fade-in-up">
        @if($result->is_passed)
            <div class="w-32 h-32 bg-emerald-100 text-emerald-500 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-xl rotate-6 hover:rotate-12 hover:scale-110 transition-all duration-300">
                <i class="fa-solid fa-face-grin-stars text-6xl -rotate-6"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter mb-4">Luar Biasa!</h1>
            <p class="text-slate-500 font-bold text-lg leading-relaxed">Lu berhasil nyelesaiin misi ini dengan skor yang kaga malu-maluin.</p>
        @else
            <div class="w-32 h-32 bg-red-100 text-red-500 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-xl -rotate-6 hover:-rotate-12 hover:scale-110 transition-all duration-300">
                <i class="fa-solid fa-face-frown-open text-6xl rotate-6"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter mb-4">Yah, Gagal...</h1>
            <p class="text-slate-500 font-bold text-lg leading-relaxed">Skor lu masih di bawah KKM lek. Belajar lagi gih, jangan kebanyakan afk!</p>
        @endif

        <div class="my-12 p-10 rounded-[3rem] {{ $result->is_passed ? 'bg-emerald-50 border-4 border-emerald-100' : 'bg-red-50 border-4 border-red-100' }} relative group hover:scale-105 transition-transform duration-300">
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-6 py-1 bg-white border-2 {{ $result->is_passed ? 'border-emerald-200 text-emerald-500' : 'border-red-200 text-red-500' }} rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                Final Result
            </div>
            <div class="text-7xl font-black {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }} tracking-tighter group-hover:scale-110 transition-transform">
                {{ $result->score }}<span class="text-2xl opacity-40 ml-1">/100</span>
            </div>
        </div>

        <div class="space-y-4 max-w-xs mx-auto">
            <a href="{{ route('student.tasks.index') }}" class="block w-full py-5 bg-emerald-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#059669] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#059669] active:translate-y-[6px] active:shadow-none transition-all text-sm">
                Lanjut Beraksi
            </a>
            @if(!$result->is_passed)
                <a href="{{ route('student.material.show', $quiz->material_id) }}" class="block w-full py-4 text-slate-400 hover:text-red-500 font-black uppercase tracking-widest text-[10px] transition-colors">
                    Baca Ulang Materinya
                </a>
            @endif
        </div>
    </div>

    @if(session()->has('level_up'))
    <div x-show="showLevelModal" 
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-[3.5rem] w-full max-w-sm p-12 text-center relative shadow-2xl border-b-[12px] border-amber-500"
             @click.outside="showLevelModal = false"
             x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-50 translate-y-20">
            
            <div class="absolute inset-0 overflow-hidden rounded-[3rem] pointer-events-none opacity-10">
                <i class="fa-solid fa-crown text-[15rem] absolute -right-10 -top-10 text-amber-500 animate-pulse"></i>
            </div>

            <div class="relative z-10">
                <div class="w-28 h-28 bg-gradient-to-tr from-amber-400 to-yellow-300 text-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-[0_0_40px_rgba(251,191,36,0.6)] animate-bounce border-4 border-white">
                    <i class="fa-solid fa-arrow-up-right-dots text-5xl"></i>
                </div>
                
                <h2 class="text-4xl font-black text-slate-800 uppercase tracking-tighter mb-2">Level Up!</h2>
                <p class="text-slate-500 font-bold mb-8 leading-relaxed text-sm">Kasta lu resmi naik dari <span class="text-slate-800 line-through decoration-red-500 decoration-2">{{ session('level_up')['old'] }}</span> jadi:</p>
                
                <div class="bg-amber-50 border-4 border-amber-200 rounded-3xl p-6 mb-10 transform hover:scale-110 hover:-rotate-3 transition-all duration-300 shadow-inner group cursor-default">
                    <p class="text-3xl font-black text-amber-600 uppercase tracking-widest group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-fire text-orange-500 mr-2 animate-pulse"></i>{{ session('level_up')['new'] }}
                    </p>
                </div>

                <button @click="showLevelModal = false" class="w-full py-5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-[0_6px_0_0_#9a3412] hover:translate-y-[2px] hover:shadow-[0_4px_0_0_#9a3412] active:translate-y-[6px] active:shadow-none transition-all text-sm flex justify-center items-center gap-2 group">
                    <span>Gue Emang Jago!</span>
                    <i class="fa-solid fa-rocket group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection