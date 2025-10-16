<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApplyOrderBy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $orderBy = $request->input('orderBy', 'id');
        $direction = $request->input('direction', 'desc');

        $request->merge([
            'orderBy' => $orderBy,
            'direction' => $direction,
        ]);

        return $next($request);
    }
}
