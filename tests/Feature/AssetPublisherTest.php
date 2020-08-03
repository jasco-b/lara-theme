<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-31
 * Time: 11:14
 */

namespace JascoB\Theme\Test\Feature;


use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Classes\AssetPublisher;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Test\Classes\TestThemeConfig;
use JascoB\Theme\VO\ThemeVo;
use PHPUnit\Framework\TestCase;

class AssetPublisherTest extends TestCase
{

    /**
     * @beforeClass
     * @afterClass
     */
    public static function cleanPublicDir()
    {

        $demoThemeConfig = static::createConfig();

        $publicDir = $demoThemeConfig->publicAssetPath();

        $fileSystem = new Filesystem();

        // copy link assets if it has been deleted
        $linkThemePath = $demoThemeConfig->themePath() . DIRECTORY_SEPARATOR . 'link' . DIRECTORY_SEPARATOR . 'assets';
        if (!$fileSystem->directories($linkThemePath)) {
            $testThemePath = $demoThemeConfig->themePath() . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'assets';
            $fileSystem->copyDirectory($testThemePath, $linkThemePath);
        }

// unlink link theme assets from public dirs
        if (is_link($publicDir . DIRECTORY_SEPARATOR . 'theme-link')) {
            unlink($publicDir . DIRECTORY_SEPARATOR . 'theme-link');
        }

        // delete public directories
        $fileSystem->deleteDirectories($publicDir);

        $fileSystem->makeDirectory($publicDir, 0755, true);
    }

    public static function createConfig($symlink = true, $assets = 'assets', $publicPath = 'public')
    {
        $config = [
            'symlink' => $symlink,
            'public_asset_path' => realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $publicPath),
            'folders' => [
                'assets' => $assets,
            ],
        ];


        return new TestThemeConfig($config);
    }

    public function createAssetPublihser(IThemeConfig $config)
    {
        return new AssetPublisher(
            new Filesystem(),
            $config
        );
    }

    public function createThemeVo($name)
    {
        $config = self::createConfig();
        $path = $config->themePath() . DIRECTORY_SEPARATOR . $name;
        return new ThemeVo($name, $path, $config->publicAssetUri());
    }

    public function testAssetPublisherMakesALink()
    {
        $theme = $this->createThemeVo('link');
        $config = self::createConfig();
        $assetPublisher = $this->createAssetPublihser($config);

        $assetPublisher->publish($theme);

        $path = $config->publicAssetPath() . DIRECTORY_SEPARATOR . $theme->getThemeNamespace();

        $this->assertTrue(file_exists($path), 'Assets is not published');
        $this->assertTrue(is_link($path), 'Link is not generated ' . $path);

        $this->assertCopiedFiles($config, $theme);

    }

    public function testAssetsPublishedWihoutLink()
    {
        $theme = $this->createThemeVo('test');
        $config = self::createConfig(false);
        $assetPublisher = $this->createAssetPublihser($config);

        $assetPublisher->publish($theme);

        $path = $config->publicAssetPath() . DIRECTORY_SEPARATOR . $theme->getThemeNamespace();

        $this->assertFalse(is_link($path), 'Dir is link');


        $this->assertCopiedFiles($config, $theme);
    }

    public function testAssetPublisherChangesAssetsForder()
    {
        $theme = $this->createThemeVo('different-asset');
        $config = self::createConfig(false, 'inc');
        $assetPublisher = $this->createAssetPublihser($config);

        $assetPublisher->publish($theme);

        $path = $config->publicAssetPath() . DIRECTORY_SEPARATOR . $theme->getThemeNamespace();

        $this->assertTrue(file_exists($path), 'Assets is not published');
        $this->assertFalse(is_link($path), 'Dir is link');
    }

    public function testThemeWithNoAssetsFolder()
    {
        $theme = $this->createThemeVo('no-assets');
        $config = self::createConfig(false);
        $assetPublisher = $this->createAssetPublihser($config);

        $assetPublisher->publish($theme);

        $path = $config->publicAssetPath() . DIRECTORY_SEPARATOR . $theme->getThemeNamespace();

        $this->assertFileNotExists($path, 'Assets is published');
    }

    public function assertCopiedFiles(IThemeConfig $config, ThemeVo $theme)
    {
        $path = $config->publicAssetPath() . DIRECTORY_SEPARATOR . $theme->getThemeNamespace();

        $assetScript = $path . DIRECTORY_SEPARATOR . 'script.js';
        $assetCssDir = $path . DIRECTORY_SEPARATOR . 'css';
        $assetCssFile = $assetCssDir . DIRECTORY_SEPARATOR . 'style.css';

        $path = $theme->getPath() . DIRECTORY_SEPARATOR . $config->assets();
        $themeScript = $path . DIRECTORY_SEPARATOR . 'script.js';
        $themeCssDir = $path . DIRECTORY_SEPARATOR . 'css';
        $themeCssFile = $themeCssDir . DIRECTORY_SEPARATOR . 'style.css';


        $this->assertTrue(is_file($assetScript), 'script.js is not published ' . $assetScript);
        $this->assertTrue(file_exists($assetCssDir), ' css dir is not published ' . $assetCssDir);
        $this->assertTrue(is_file($assetCssFile), 'css file is not published' . $assetCssFile);
        $this->assertEquals(filesize($assetScript), filesize($themeScript), 'scripts is not the same ' . $themeScript);
        $this->assertEquals(filesize($assetCssDir), filesize($themeCssDir), 'css dir is not the same ' . $themeCssDir);
        $this->assertEquals(filesize($assetCssFile), filesize($themeCssFile), 'css file is not the same ' . $themeCssFile);
    }


}
