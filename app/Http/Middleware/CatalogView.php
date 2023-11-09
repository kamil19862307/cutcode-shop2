<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CatalogView
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('view')){
            $request->session()->put('view', $request->get('view'));
        }

        return $next($request);
    }
}
