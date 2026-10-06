<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the visitor's saved language, or detect it from the browser.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['en', 'ms', 'zh']);
        $locale = $request->session()->get('locale');

        if (! is_string($locale) || ! in_array($locale, $supportedLocales, true)) {
            $locale = $request->getPreferredLanguage($supportedLocales)
                ?? config('app.fallback_locale', 'en');

            $request->session()->put('locale', $locale);
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
