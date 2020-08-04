<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-08-03
 * Time: 19:06
 */

namespace App\Packages\theme\src\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Facades\Theme;
use JascoB\Theme\VO\ThemeVo;

class ThemeClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:create {name?}';

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

        if ($name) {

            if (!($themeVo = Theme::get($name))) {
                $this->info($name . ' theme not found');
                return;
            }

            $this->clearThemeAssets($themeVo, $filesystem, $config);

            $this->info($name . ' assets cleared');
            return;
        }

        $list = Theme::all();

        foreach ($list as $themeVo) {
            $this->clearThemeAssets($themeVo, $filesystem, $config);
        }

        $this->info('all theme assets cleared');
    }

    protected function clearThemeAssets(ThemeVo $vo, Filesystem $filesystem, IThemeConfig $config)
    {
        $public_path_link = $config->publicAssetPath() . DIRECTORY_SEPARATOR . $vo->getThemeNamespace();

        if (is_link($public_path_link)) {
            unlink($public_path_link);
        } elseif (is_file($public_path_link)) {
            unlink($public_path_link);
        } else {
            $filesystem->deleteDirectory($public_path_link);
        }
    }


}
