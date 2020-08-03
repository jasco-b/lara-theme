<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:16
 */

namespace JascoB\Theme\Providers;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use JascoB\Theme\Classes\AssetPublisher;
use JascoB\Theme\Classes\ThemeConfig;
use JascoB\Theme\Classes\ThemeLoader;
use JascoB\Theme\Classes\ThemeViewParser;
use JascoB\Theme\Commands\ThemeCreateCommand;
use JascoB\Theme\Commands\ThemeDeleteCommand;
use JascoB\Theme\Commands\ThemeDuplicateCommand;
use JascoB\Theme\Commands\ThemeListCommand;
use JascoB\Theme\Contracts\IAssetPublisher;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Contracts\IThemeLoader;
use JascoB\Theme\Contracts\IThemeViewParser;
use JascoB\Theme\Middleware\ThemeMiddleware;
use JascoB\Theme\Theme;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerContracts();

        $this->app->singleton('Jasco-theme', Theme::class);

        $this->publishes([
            __DIR__ . '/../../config/theme.php' => config_path('theme.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../../template' => self::laraPath(),
        ], 'template');

        $this->registerCommands();
    }

    public function boot()
    {
        $config = $this->app->get(IThemeConfig::class);

        if ($config->active() && \Theme::get($config->active())) {
            \Theme::set($config->active());
        }

        $this->app['router']->aliasMiddleware('theme', ThemeMiddleware::class);

        $this->includeHelper();

        $this->registerDerectives();

    }

    protected function registerContracts()
    {
        $this->app->singleton(IThemeConfig::class, ThemeConfig::class);
        $this->app->singleton(IAssetPublisher::class, AssetPublisher::class);
        $this->app->singleton(IThemeLoader::class, ThemeLoader::class);
        $this->app->singleton(IThemeViewParser::class, ThemeViewParser::class);
    }

    public function registerCommands()
    {
        $this->commands([
            ThemeListCommand::class,
            ThemeDeleteCommand::class,
            ThemeDuplicateCommand::class,
            ThemeCreateCommand::class,
        ]);
    }

    public static function laraPath()
    {
        return resource_path('views/vendor/theme/template');
    }

    public function includeHelper()
    {
        require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'helpers.php';
    }

    public function registerDerectives()
    {
        Blade::directive('themeInclude', function ($expression) {
            return "<?php echo Theme::include($expression)->toHtml() ?>";
        });

        Blade::directive('themeFirst', function ($expression) {
            return "<?php echo Theme::first($expression)->toHtml() ?>";
        });
    }
}
