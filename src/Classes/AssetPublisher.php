<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 17:36
 */

namespace JascoB\Theme\Classes;


use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Contracts\IAssetPublisher;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\VO\ThemeVo;

class AssetPublisher implements IAssetPublisher
{
    /**
     * @var ThemeConfig
     */
    private $config;
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var $theme ThemeVo
     */
    private $theme;

    public function __construct(Filesystem $filesystem, IThemeConfig $config)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    public function publish(ThemeVo $theme)
    {
        $this->theme = $theme;


        if ($this->isAssetsPublished()) {
            return;
        }

        if (!$this->filesystem->exists($this->themeAssetsPath())) {
            return;
        }

        if ($this->config->needSymlink()) {
            $this->makeSymlink();
            return;
        }

        $this->copyAssets();
    }

    private function isAssetsPublished()
    {
        return $this->filesystem->exists($this->publicAssetsPath());
    }

    private function makeSymlink()
    {
        if (!$this->filesystem->isDirectory($this->config->publicAssetPath())) {
            $this->filesystem->makeDirectory($this->config->publicAssetPath(), 0755, true);
        }

        symlink($this->themeAssetsPath(), $this->publicAssetsPath());
    }

    private function copyAssets()
    {
        $this->filesystem->copyDirectory($this->themeAssetsPath(), $this->publicAssetsPath());
    }

    private function publicAssetsPath()
    {
        return $this->config->publicAssetPath() . DIRECTORY_SEPARATOR . $this->theme->getThemeNamespace();
    }

    private function themeAssetsPath()
    {
        return $this->theme->getPath() . DIRECTORY_SEPARATOR . $this->config->assets();
    }

}
