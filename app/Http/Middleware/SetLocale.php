<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and apply user's preferred language.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', config('app.locale', 'en'));

        if (!in_array($locale, ['en', 'km'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
