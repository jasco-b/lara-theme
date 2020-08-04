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
    function theme()
    {
        return \JascoB\Theme\Facades\Theme::get();
    }
}

if (!function_exists('themeAsset')) {
    function themeAsset($asset)
    {
        $theme = \JascoB\Theme\Facades\Theme::get();

        if ($theme) {

            return asset($theme->getUrl() . '/' . $asset);
        }

        return asset($asset);
    }
}
