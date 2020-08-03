<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:13
 */

namespace JascoB\Theme\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Facades\Theme;
use JascoB\Theme\Providers\ThemeServiceProvider;
use JascoB\Theme\Traits\ConfigFileModifier;

class ThemeCreateCommand extends Command
{
    use ConfigFileModifier;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create theme';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem, IThemeConfig $config)
    {
        $name = $this->argument('name');

        if (Theme::get($name)) {
            $this->error(sprintf('%s has already exist', $name));
            return;
        }

        if ($filesystem->exists($templateDir = $config->themePath() . DIRECTORY_SEPARATOR . $name)) {
            $this->error(sprintf('%s dir already exists', $templateDir));
            return;
        }

        if ($filesystem->copyDirectory($this->path($filesystem), $templateDir) === false) {
            $this->error(sprintf('cannot create template %s', $templateDir));
            return;
        }


        if ($this->modifyConfig($templateDir, $name, $config) === false) {
            $this->error('Cannot modify config');
            return;
        }

        $this->info(sprintf('Done: %s', $templateDir));

    }

    protected function path(Filesystem $filesystem)
    {
        return $filesystem->exists($path = ThemeServiceProvider::laraPath())
            ? $path
            : __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'template';
    }


}
