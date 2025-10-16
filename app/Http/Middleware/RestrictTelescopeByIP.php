<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictTelescopeByIP
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
//        $allowedIPs = [
//            '127.0.0.1',          // localhost
//            '192.168.1.1',        // your IP
//            '::1'                 // IPv6 localhost
//        ];
//
//        if (!in_array($request->ip(), $allowedIPs)) {
//            abort(403, 'Unauthorized access');
//        }

        return $next($request);

    }
}
