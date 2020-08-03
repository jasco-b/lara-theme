<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:14
 */

namespace JascoB\Theme\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Facades\Theme;
use JascoB\Theme\Traits\ConfigFileModifier;

class ThemeDuplicateCommand extends Command
{
    use ConfigFileModifier;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:duplicate {current} {new}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create duplicate theme';

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
        $current = Theme::get($this->argument('current'));

        if (!$current) {
            $this->error(sprintf('Theme %s not found', $this->argument('current')));
            return;
        }

        $newThemeName = $this->argument('new');

        $new = Theme::get($newThemeName);

        if ($new) {
            $this->error(sprintf('Theme %s already exists', $newThemeName));
            return;
        }

        $newThemePath = $config->themePath() . DIRECTORY_SEPARATOR . $newThemeName;

        if ($filesystem->exists($newThemePath)) {
            $this->error(sprintf('%s path already exists', $newThemePath));
            return;
        }

        if ($filesystem->copyDirectory($current->getPath(), $newThemePath) === false) {
            $this->error('Cannot copy theme');
            return;
        }

        if ($this->modifyConfig($newThemePath, $newThemeName, $config) === false) {
            $this->error('Cannot modify config');
            return;
        }

        $this->info(sprintf('%s has been created at %s', $newThemeName, $newThemePath));
    }


}
