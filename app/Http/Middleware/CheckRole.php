<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next ,$roles): Response
    {
        $roles = explode('|', $roles);
        
        /** @var \App\Models\User|null $user */ // <-- أضف هذا السطر
        $user = Auth::user();

        foreach ($roles as $role) {
            // الآن سيفهم المحرر أن hasRole موجودة
            if ($user?->hasRole($role)) { 
                return $next($request);
            }
        }
        abort(403, 'Unauthorized action.');
    }
}