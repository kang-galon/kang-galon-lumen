<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->status != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Access forbidden',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
