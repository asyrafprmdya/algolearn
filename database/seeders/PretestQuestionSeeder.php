<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PretestQuestion; // <-- INI YANG BENER LEK!

class PretestQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Di C++, tipe data apa yang paling cocok buat nyimpen angka bulat tanpa koma?',
                'option_a' => 'float',
                'option_b' => 'char',
                'option_c' => 'int',
                'option_d' => 'string',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Apa output dari kode: int a = 5; cout << a + 3;',
                'option_a' => '53',
                'option_b' => '8',
                'option_c' => '2',
                'option_d' => 'error',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Simbol apa yang dipakai untuk operasi sisa bagi di C++?',
                'option_a' => '/',
                'option_b' => '%',
                'option_c' => '*',
                'option_d' => '+',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Struktur kontrol apa yang dipakai untuk percabangan di C++?',
                'option_a' => 'for',
                'option_b' => 'while',
                'option_c' => 'if',
                'option_d' => 'loop',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Apa output dari kode: for(int i=0; i<3; i++) cout << i;',
                'option_a' => '123',
                'option_b' => '012',
                'option_c' => '321',
                'option_d' => 'error',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Kenapa Binary Search lebih cepat dibanding Linear Search?',
                'option_a' => 'Karena pakai pointer',
                'option_b' => 'Karena membagi data jadi dua setiap langkah',
                'option_c' => 'Karena tidak pakai loop',
                'option_d' => 'Karena hanya untuk integer',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Apa yang terjadi jika rekursi tidak memiliki base case?',
                'option_a' => 'Program berhenti normal',
                'option_b' => 'Program error saat compile',
                'option_c' => 'Terjadi infinite recursion sampai crash',
                'option_d' => 'Program jadi lebih cepat',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Berapa kompleksitas waktu dari nested loop 2 tingkat (n x n)?',
                'option_a' => 'O(n)',
                'option_b' => 'O(log n)',
                'option_c' => 'O(n^2)',
                'option_d' => 'O(1)',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Apa output dari kode berikut: int a=5; int &b=a; b=10; cout<<a;',
                'option_a' => '5',
                'option_b' => '10',
                'option_c' => 'error',
                'option_d' => '0',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Mana algoritma sorting dengan kompleksitas rata-rata O(n log n)?',
                'option_a' => 'Bubble Sort',
                'option_b' => 'Selection Sort',
                'option_c' => 'Quick Sort',
                'option_d' => 'Insertion Sort',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Apa perbedaan utama array dan pointer di C++?',
                'option_a' => 'Array bisa diubah ukurannya',
                'option_b' => 'Pointer menyimpan alamat memori',
                'option_c' => 'Array tidak bisa diakses index',
                'option_d' => 'Pointer tidak bisa digunakan di array',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Jika sebuah algoritma memiliki kompleksitas O(log n), artinya apa?',
                'option_a' => 'Waktu eksekusi konstan',
                'option_b' => 'Waktu bertambah linear',
                'option_c' => 'Waktu bertambah sangat cepat',
                'option_d' => 'Waktu bertambah lambat walau data besar',
                'correct_answer' => 'd',
            ],
            [
                'question' => 'Apa fungsi dari perintah "break" di dalam sebuah loop?',
                'option_a' => 'Menghentikan program seutuhnya',
                'option_b' => 'Melewati satu iterasi dan lanjut ke berikutnya',
                'option_c' => 'Keluar dari loop secara paksa',
                'option_d' => 'Menghapus variabel di dalam loop',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Perintah apa yang digunakan untuk melewati sisa kode di dalam loop dan langsung ke iterasi berikutnya?',
                'option_a' => 'break',
                'option_b' => 'continue',
                'option_c' => 'return',
                'option_d' => 'exit',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Apa yang terjadi kalau kamu mengakses indeks array yang di luar batas (Out of Bounds) di C++?',
                'option_a' => 'Pasti langsung Error Compile',
                'option_b' => 'Array otomatis membesar',
                'option_c' => 'Undefined behavior / Menampilkan nilai sampah (garbage)',
                'option_d' => 'Mengembalikan nilai 0',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Operator apa yang digunakan untuk mendapatkan alamat memori dari sebuah variabel?',
                'option_a' => '*',
                'option_b' => '&',
                'option_c' => '->',
                'option_d' => '%',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Kata kunci (keyword) apa yang dipakai di C++ untuk alokasi memori dinamis (di Heap)?',
                'option_a' => 'malloc',
                'option_b' => 'alloc',
                'option_c' => 'new',
                'option_d' => 'create',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Jika kita mendeklarasikan "int *ptr;", maka ptr adalah...',
                'option_a' => 'Variabel integer biasa',
                'option_b' => 'Sebuah pointer yang menunjuk ke tipe integer',
                'option_c' => 'Sebuah array integer',
                'option_d' => 'Sebuah fungsi yang mengembalikan integer',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Konsep membungkus data dan fungsi yang mengelolanya ke dalam satu unit (class) disebut?',
                'option_a' => 'Polimorfisme',
                'option_b' => 'Inheritance',
                'option_c' => 'Enkapsulasi',
                'option_d' => 'Abstraksi',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Berapa kompleksitas waktu (Time Complexity) untuk menyisipkan (insert) elemen di posisi paling depan pada sebuah Array biasa?',
                'option_a' => 'O(1)',
                'option_b' => 'O(log n)',
                'option_c' => 'O(n)',
                'option_d' => 'O(n^2)',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Di C++, secara default parameter dikirimkan ke dalam fungsi melalui metode apa?',
                'option_a' => 'Pass by Reference',
                'option_b' => 'Pass by Pointer',
                'option_c' => 'Pass by Value',
                'option_d' => 'Pass by Object',
                'correct_answer' => 'c',
            ],
            [
                'question' => 'Tipe data boolean (bool) di C++ menempati memori sebesar?',
                'option_a' => '1 bit',
                'option_b' => '1 byte',
                'option_c' => '4 byte',
                'option_d' => '8 byte',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Apa perbedaan antara operator = dan == di C++?',
                'option_a' => 'Keduanya sama, bisa dipakai bergantian',
                'option_b' => '= untuk Assignment (pengisian nilai), == untuk Relational (perbandingan)',
                'option_c' => '== untuk Assignment, = untuk Relational',
                'option_d' => '== hanya bisa dipakai untuk string',
                'correct_answer' => 'b',
            ],
            [
                'question' => 'Dalam konsep OOP C++, method yang otomatis terpanggil ketika objek dihancurkan atau keluar dari scope disebut?',
                'option_a' => 'Constructor',
                'option_b' => 'Finalizer',
                'option_c' => 'Terminator',
                'option_d' => 'Destructor',
                'correct_answer' => 'd',
            ],
            [
                'question' => 'Operator logika AND dan OR di C++ secara berurutan ditulis dengan simbol apa?',
                'option_a' => '&& dan ||',
                'option_b' => '& dan |',
                'option_c' => 'AND dan OR',
                'option_d' => '++ dan --',
                'correct_answer' => 'a',
            ]
        ];

        PretestQuestion::truncate(); // <-- KOSONGIN PAKE MODEL YANG BENER

        foreach ($questions as $q) {
            PretestQuestion::create($q); // <-- BIKIN DATA PAKE MODEL YANG BENER
        }
    }
}