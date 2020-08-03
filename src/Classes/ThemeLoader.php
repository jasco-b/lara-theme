<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 18:27
 */

namespace JascoB\Theme\Classes;


use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Contracts\IThemeLoader;
use JascoB\Theme\VO\ThemeVo;

class ThemeLoader implements IThemeLoader
{
    /**
     * @var ThemeConfig
     */
    private $config;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem, IThemeConfig $config)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    /**
     * @return array
     */
    public function loadAll()
    {
        $list = [];

        $pathList = $this->filesystem->directories($this->config->themePath());


        foreach ($pathList as $themePath) {
            $configPath = $themePath . DIRECTORY_SEPARATOR . $this->config->configFile();
            if (!is_file($configPath)) {
                continue;
            }

            $theme = $this->load($configPath);

            if ($theme) {
                $list[$theme['name']] = new ThemeVo(
                    $theme['name'],
                    $themePath,
                    $this->config->publicAssetUri(),
                    $theme
                );
            }

        }

        return $list;
    }

    protected function load($configPath)
    {
        $theme = (array)json_decode(file_get_contents($configPath), 1);

        if (!is_array($theme)) {
            return null;
        }

        if (!isset($theme['name'])) {
            return null;
        }

        return [
            'name' => $theme['name'],
            'description' => $theme['description'] ?? '',
            'version' => $theme['description'] ?? '',
        ];
    }
}
