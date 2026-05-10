@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-slate-50 overflow-hidden">
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shrink-0">
        <div class="flex-1 overflow-y-auto">
            <div class="h-20 flex items-center px-6 border-b border-slate-100 mb-4 sticky top-0 bg-white z-10">
                <div class="flex items-center space-x-3 text-[#0b276b]">
                    <div class="bg-amber-500 text-white p-2 rounded-lg">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg leading-tight">Portal<br>Dosen</h2>
                    </div>
                </div>
            </div>
            <nav class="px-4 space-y-1 mb-4">
                <a href="{{ route('lecturer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-amber-500 text-white rounded-lg font-medium text-sm">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto" x-data="{
                questions: [{ text: '', a: '', b: '', c: '', d: '', answer: 'a' }],
                addQuestion() { this.questions.push({ text: '', a: '', b: '', c: '', d: '', answer: 'a' }); },
                removeQuestion(index) { this.questions.splice(index, 1); }
            }">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Buat Kuis: {{ $material->title }}</h1>
                    <p class="text-slate-500">Siksa mahasiswamu dengan pertanyaan-pertanyaan menjebak di sini.</p>
                </div>

                <form action="{{ route('lecturer.quiz.store', $material->id) }}" method="POST">
                    @csrf
                    
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Judul Kuis</label>
                                <input type="text" name="title" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500" placeholder="Contoh: Evaluasi Array">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nilai Lulus (KKM)</label>
                                <input type="number" name="passing_grade" value="70" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                            </div>
                        </div>
                    </div>

                    <template x-for="(q, index) in questions" :key="index">
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6 relative">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-slate-800">Soal #<span x-text="index + 1"></span></h3>
                                <button type="button" @click="removeQuestion(index)" x-show="questions.length > 1" class="text-red-500 hover:text-red-700 text-sm font-bold"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </div>

                            <textarea x-bind:name="'questions['+index+'][question_text]'" x-model="q.text" required rows="3" class="w-full px-4 py-3 rounded-lg border border-slate-300 mb-4 focus:ring-2 focus:ring-amber-500" placeholder="Tulis pertanyaan di sini..."></textarea>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div><span class="text-xs font-bold text-slate-500">Opsi A</span><input type="text" x-bind:name="'questions['+index+'][option_a]'" x-model="q.a" required class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500"></div>
                                <div><span class="text-xs font-bold text-slate-500">Opsi B</span><input type="text" x-bind:name="'questions['+index+'][option_b]'" x-model="q.b" required class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500"></div>
                                <div><span class="text-xs font-bold text-slate-500">Opsi C</span><input type="text" x-bind:name="'questions['+index+'][option_c]'" x-model="q.c" required class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500"></div>
                                <div><span class="text-xs font-bold text-slate-500">Opsi D</span><input type="text" x-bind:name="'questions['+index+'][option_d]'" x-model="q.d" required class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500"></div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-bold text-slate-700 mr-4">Jawaban Benar:</label>
                                <select x-bind:name="'questions['+index+'][correct_option]'" x-model="q.answer" class="px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500 bg-white">
                                    <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                                </select>
                            </div>
                        </div>
                    </template>

                    <div class="flex space-x-4 mb-8">
                        <button type="button" @click="addQuestion()" class="flex-1 py-3 border-2 border-dashed border-amber-500 text-amber-600 font-bold rounded-xl hover:bg-amber-50 transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Soal Lain
                        </button>
                        <button type="submit" class="flex-1 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors shadow-md">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Kuis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection