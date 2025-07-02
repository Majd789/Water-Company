<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissions): Response
    {

        // التحقق من أن المستخدم لديه أي من الصلاحيات المطلوبة
        // في حال كان يتمتلك واحدة من الصلاحيات المدخلة يتم القبول
        $permissions = explode('|', $permissions);
        foreach ($permissions as $perm) {
            if (Auth::user()->can($perm)) {
                return $next($request);
            }
        }
         abort(403, 'Unauthorized action.');
    }
}
