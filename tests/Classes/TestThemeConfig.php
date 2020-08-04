<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-31
 * Time: 10:11
 */

namespace JascoB\Theme\Test\Classes;


use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Traits\GetArrayValueTrait;

class TestThemeConfig implements IThemeConfig
{
    use GetArrayValueTrait;

    protected $config = [];

    public function __construct($data = [])
    {

        $default = [
            'active' => '',
            'theme_path' => (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'themes'),
            'symlink' => false,
            'public_asset_path' => (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets'),
            'config' => [
                'name' => 'config.json',
            ],
            'folders' => [
                'assets' => 'assets',
            ],
            'cache' => true,
            'public_asset_uri' => 'assets',
        ];

        $this->config = array_merge($default, (array)$data);
    }

    public function active()
    {
        return $this->getValue($this->config, 'active');
    }

    public function themePath()
    {
        return $this->getValue($this->config, 'theme_path');
    }

    public function needSymlink()
    {
        return $this->getValue($this->config, 'symlink');
    }

    public function publicAssetPath()
    {
        return $this->getValue($this->config, 'public_asset_path');
    }

    public function configFile()
    {
        return $this->getValue($this->config, 'config.name');
    }

    public function assets()
    {
        return $this->getValue($this->config, 'folders.assets');
    }

    public function shouldCache()
    {
        return $this->getValue($this->config, 'cache');
    }

    public function publicAssetUri()
    {
        return $this->getValue($this->config, 'public_asset_uri');
    }
}
