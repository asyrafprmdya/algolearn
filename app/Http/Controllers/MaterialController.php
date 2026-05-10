<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::where('is_published', true)->get();
        return view('student.material.index', compact('materials'));
    }

    public function show(Material $material)
    {
        Auth::user()->accessedMaterials()->syncWithoutDetaching([$material->id]);
        
        $material->load('quizzes.questions');
        return view('student.material.show', compact('material'));
    }
}