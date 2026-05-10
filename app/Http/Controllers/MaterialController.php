<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $userLevel = Auth::user()->level; 

        $allowedLevels = match($userLevel) {
            'Lanjutan' => ['Pemula', 'Menengah', 'Lanjutan'],
            'Menengah' => ['Pemula', 'Menengah'],
            default => ['Pemula'],
        };

        $materials = Material::where('is_published', true)
                             ->whereIn('level', $allowedLevels)
                             ->get();

        return view('student.material.index', compact('materials'));
    }

    public function show(Material $material)
    {
        $userLevel = Auth::user()->level;

        $allowedLevels = match($userLevel) {
            'Lanjutan' => ['Pemula', 'Menengah', 'Lanjutan'],
            'Menengah' => ['Pemula', 'Menengah'],
            default => ['Pemula'],
        };

        if (!in_array($material->level, $allowedLevels)) {
            return redirect()->route('student.material.index')
                             ->with('error', 'Akses ditolak.');
        }

        Auth::user()->accessedMaterials()->syncWithoutDetaching([$material->id]);
        
        $material->load('quizzes.questions');
        return view('student.material.show', compact('material'));
    }
}