<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;

class IsPenjual
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->role != 'Pembeli') {
            return $next($request);
        }

        return ResponseFormatter::error(
            null,
            'Unauthorized. hanya penjual dan admin yang bisa akses.',
            404,
        );

    }
}
