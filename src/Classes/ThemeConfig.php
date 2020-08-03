<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 18:00
 */

namespace JascoB\Theme\Classes;


use Illuminate\Support\Facades\Config;
use JascoB\Theme\Contracts\IThemeConfig;

class ThemeConfig implements IThemeConfig
{
    public function active()
    {
        return Config::get('theme.active');
    }

    public function themePath()
    {
        return (string)Config::get('theme.theme_path');
    }

    public function needSymlink()
    {
        return (boolean)Config::get('theme.symlink');
    }

    public function publicAssetPath()
    {
        return Config::get('theme.public_asset_path');
    }

    public function configFile()
    {
        return Config::get('theme.config.name');
    }

    public function assets()
    {
        return Config::get('theme.folders.assets');
    }

    public function shouldCache()
    {
        return Config::get('theme.cache');
    }

    public function shouldReplaceView()
    {
        return Config::get('theme.replaceView');
    }

    public function publicAssetUri()
    {
        return Config::get('theme.public_asset_uri');
    }

}
