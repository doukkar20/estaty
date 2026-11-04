<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ChangeLanguage
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    if (!is_file(storage_path('installed')) || $request->attributes->get('siteLocaleResolved')) return $next($request);
    $selected = $request->session()->get('currentLocaleCode');
    $language = is_string($selected) && $selected !== ''
      ? Language::where('code', $selected)->first() : null;
    if (!$language) {
      $language = Language::where('is_default', 1)->first();
      if ($selected !== null) $request->session()->forget('currentLocaleCode');
    }
    App::setLocale($language->code ?? config('app.locale', 'fr'));
    $request->attributes->set('siteLocaleResolved', true);

    return $next($request);
  }
}
