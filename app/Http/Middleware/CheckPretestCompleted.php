<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPretestCompleted
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->isStudent() && !Auth::user()->hasCompletedPretest()) {
            return redirect()->route('student.pretest.index');
        }
        return $next($request);
    }
}