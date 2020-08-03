<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 18:27
 */

namespace JascoB\Theme\Contracts;

interface IThemeConfig
{
    public function active();

    public function themePath();

    public function needSymlink();

    public function publicAssetPath();

    public function configFile();

    public function assets();

    public function shouldCache();

    public function shouldReplaceView();

    public function publicAssetUri();
}
