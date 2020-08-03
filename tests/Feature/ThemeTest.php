<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-31
 * Time: 14:22
 */

namespace JascoB\Theme\Test\Feature;


use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Exceptions\ThemeNotFoundException;
use JascoB\Theme\Providers\ThemeServiceProvider;
use JascoB\Theme\Test\Classes\TestThemeConfig;
use JascoB\Theme\Theme;
use JascoB\Theme\VO\ThemeVo;
use Orchestra\Testbench\TestCase;

class ThemeTest extends TestCase
{

    /**
     * @after
     */
    public function clearPublicAssets()
    {
        $config = new TestThemeConfig();
        $fs = new Filesystem();
        if ($fs->exists($config->publicAssetPath())) {
            $fs->deleteDirectories($config->publicAssetPath());
        }

    }

    protected function getPackageProviders($app)
    {
        return [ThemeServiceProvider::class];
    }

    public function createTheme($config = [])
    {
        $this->app->singleton(IThemeConfig::class, function () use ($config) {
            return new TestThemeConfig($config);
        });
        return $this->app->make(Theme::class);
    }

    public function testLoadThemes()
    {

        $theme = $this->createTheme();

        $this->assertCount(4, $theme->all());
        $this->assertArrayHasKey('test', $theme->all());
        $this->assertArrayHasKey('link', $theme->all());
        $this->assertArrayHasKey('different-asset', $theme->all());
        $this->assertArrayHasKey('no-assets', $theme->all());

        $this->assertArrayNotHasKey('config-error', $theme->all());
        $this->assertArrayNotHasKey('wrong-config', $theme->all());
        $this->assertArrayNotHasKey('no-config', $theme->all());
    }

    public function testSetTheme()
    {
        $theme = $this->createTheme();

        $testName = 'test';
        $theme->set($testName);

        $vo = $theme->get();
        $this->assertInstanceOf(ThemeVo::class, $vo);

        $this->assertEquals($testName, $vo->getName());

        $this->expectException(ThemeNotFoundException::class);
        $testName = 'no-config';
        $theme->set($testName);
    }

    public function testThemePublishedAssets()
    {
        $theme = $this->createTheme();

        $testName = 'test';
        $theme->set($testName);

        $vo = $theme->get();

        /**
         * @var $config IThemeConfig
         */
        $config = $this->app->get(IThemeConfig::class);

        $this->assertFileExists($config->publicAssetPath() . DIRECTORY_SEPARATOR . $vo->getThemeNamespace());
    }

    public function testGetValidTheme()
    {
        $theme = $this->createTheme();

        $testName = 'test';

        $vo = $theme->get($testName);

        $this->assertInstanceOf(ThemeVo::class, $vo);

        $this->assertEquals($testName, $vo->getName());
    }

    public function testGetInvalidTheme()
    {
        $theme = $this->createTheme();
        $testName = 'no-config';
        $vo = $theme->get($testName);

        $this->assertNull($vo);

    }

    public function testHasValidTheme()
    {
        $theme = $this->createTheme();

        $this->assertTrue($theme->has('test'));
    }

    public function testHasInvalidTheme()
    {
        $theme = $this->createTheme();

        $this->assertFalse($theme->has('no-config'));

        $this->assertFalse($theme->has(time()));
    }

    public function testAddLocationWhenReplaceViewTrue()
    {
        $theme = $this->createTheme(
            [
                'replaceView' => true
            ]
        );

        $theme->set('test');

        $vo = $theme->get();

        $viewFactory = $theme->getViewFactory();

        $views = $viewFactory->getFinder()->getPaths();

        $this->assertContains($vo->getPath(), $views);

        $this->assertEquals($vo->getPath(), current($views));
    }

    public function testAddLocationWhenReplaceViewFalse()
    {
        $theme = $this->createTheme();
        $theme->set('test');

        $vo = $theme->get();

        $viewFactory = $theme->getViewFactory();

        $views = $viewFactory->getFinder()->getPaths();

        $this->assertNotContains($vo->getPath(), $views);
    }

    public function testAddNamespace()
    {
        $theme = $this->createTheme();
        $theme->set('test');

        $vo = $theme->get('test');

        $viewFactory = $theme->getViewFactory();

        $hints = $viewFactory->getFinder()->getHints();

        $this->assertArrayHasKey($vo->getThemeNamespace(), $hints);

        $this->assertEquals($vo->getPath(), current($hints[$vo->getThemeNamespace()]));
    }

}
