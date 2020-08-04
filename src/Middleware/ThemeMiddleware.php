<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 16:27
 */

namespace JascoB\Theme\Middleware;


use Closure;
use JascoB\Theme\Facades\Theme;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $theme)
    {

        if (Theme::has($theme)) {
            Theme::set($theme);
        }



        return $next($request);
    }
}
