<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permissions): Response
    {
        /** @var \App\Models\User|null $user */ // <-- أضف هذا السطر
        $user = Auth::user();
        
        $permissions = explode('|', $permissions);
        foreach ($permissions as $perm) {
            // الآن سيفهم المحرر أن can موجودة
            if ($user?->can($perm)) { 
                return $next($request);
            }
        }
         abort(403, 'Unauthorized action.');
    }
}