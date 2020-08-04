<?php
/**
 * @var $theme \JascoB\Theme\VO\ThemeVo
 */
if (!function_exists('theme_uri')) {
    function theme_uri()
    {
        $theme = \JascoB\Theme\Facades\Theme::get();

        return $theme->getUrl();
    }
}


if (!function_exists('theme_path')) {
    function theme_path()
    {
        $theme = \JascoB\Theme\Facades\Theme::get();

        return $theme->getPath();
    }
}


if (!function_exists('theme')) {
    function theme($name = null)
    {
        $theme = app('Jasco-theme')->get();

        if ($name) {
            return $theme->info($name);
        }

        return $theme;
    }
}

if (!function_exists('theme_asset')) {
    function theme_asset($asset, $secure = null)
    {
        return app('Jasco-theme')->asset($asset, $secure);
    }
}
