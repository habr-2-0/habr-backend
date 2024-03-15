<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('App-Locale') == 'ru') {
            App::setLocale('ru');
        } else if ($request->header('App-Locale') == 'en') {
            App::setLocale('en');
        }


        return $next($request);
    }
}
