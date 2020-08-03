<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-31
 * Time: 10:44
 */

namespace JascoB\Theme\Test\Feature;


use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Classes\ThemeLoader;
use JascoB\Theme\Test\Classes\TestThemeConfig;
use JascoB\Theme\VO\ThemeVo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class ThemeLoaderTest extends TestCase
{
    protected function createConfig($themeDir = null)
    {
        $config = [];
        if ($themeDir) {
            $config['theme_path'] = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $themeDir;
        }

        return new TestThemeConfig($config);
    }

    protected function loader($themeDir = null)
    {
        return new ThemeLoader(new Filesystem(), $this->createConfig($themeDir));
    }

    public function testThemeIsLoading()
    {
        $themes = $this->loader()->loadAll();

        $this->assertCount(4, $themes, 'theme is not Loading');

    }

    public function testThemeIsNotLoadingWithoutConfig()
    {
        $themes = $this->loader()->loadAll();

        $this->assertArrayNotHasKey('no-config', $themes);
    }

    public function testThemeIsLoadingValidThemes()
    {
        $themes = $this->loader()->loadAll();

        $this->assertCount(4, $themes);

        $this->assertArrayHasKey('link', $themes);

        $this->assertArrayHasKey('test', $themes);

        $this->assertArrayHasKey('different-asset', $themes);

        $this->assertArrayHasKey('no-assets', $themes);

    }

    public function testThemeIsNotLoadingInvalidConfig()
    {
        $themes = $this->loader()->loadAll();


        $this->assertArrayNotHasKey('config-error', $themes);
    }

    public function testThemeIsNotLoadingWithWrongConfig()
    {
        $themes = $this->loader()->loadAll();


        $this->assertArrayNotHasKey('wrong-config', $themes);

    }


    public function testThemeWithWrongNotExsitsDir()
    {

        $themes = $this->loader('no-idea')->loadAll();

        $this->assertEmpty($themes);
    }

    public function testThemeThemeWIthExsistButWithoutTheme()
    {
        $themes = $this->loader('Classes')->loadAll();

        $this->assertCount(0, $themes);
    }

    public function testThemeLoadingWithEmptyDir()
    {
        $themes = $this->loader('public')->loadAll();

        $this->assertCount(0, $themes);
    }

    public function testLoadedThemeIsArrayOfThemeVo()
    {
        $themes = $this->loader()->loadAll();
        $testTheme = $themes['test'];

        $this->assertInstanceOf(ThemeVo::class, $testTheme, 'Loaded theme is not instance of ThemeVo');

        $config = $this->createConfig();
        $this->assertEquals($config->themePath() . DIRECTORY_SEPARATOR . 'test', $testTheme->getPath());
    }
}
