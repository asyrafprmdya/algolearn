<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        // Tarik semua materi yang udah rilis, terus urutin paksa: Pemula -> Menengah -> Lanjutan
        $materials = Material::where('is_published', true)
                             ->orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")
                             ->get();

        return view('student.material.index', compact('materials'));
    }

    public function show(Material $material)
    {
        $userLevel = Auth::user()->level;

        // Aturan Kasta tetep jalan buat SATPAM BELAKANG
        $allowedLevels = match($userLevel) {
            'Lanjutan' => ['Pemula', 'Menengah', 'Lanjutan'],
            'Menengah' => ['Pemula', 'Menengah'],
            default => ['Pemula'],
        };

        // Kalau ada maba iseng nembak URL materi elit, tendang balik!
        if (!in_array($material->level, $allowedLevels)) {
            return redirect()->route('student.material.index')
                             ->with('error', 'Kasta lu belum nyampe buat buka kitab rahasia ini lek!');
        }

        // Catat di buku tamu: mahasiswa ini udah baca materi ini
        Auth::user()->accessedMaterials()->syncWithoutDetaching([$material->id]);
        
        $material->load('quizzes.questions');
        return view('student.material.show', compact('material'));
    }
}