<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $permissions = array_filter(array_map('trim', explode('|', $permission)));

        foreach ($permissions as $perm) {
            if ($user->hasPermission($perm)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
    }
}
