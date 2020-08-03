<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:13
 */

namespace JascoB\Theme\Commands;


use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use JascoB\Theme\Facades\Theme;

class ThemeDeleteCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:delete {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete theme';

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
    public function handle(Filesystem $filesystem)
    {
        $theme = Theme::get($this->argument('name'));

        if (!$theme) {
            $this->error(sprintf('Theme %s not found', $this->argument('name')));
            return;
        }

        if ($filesystem->deleteDirectory($theme->getPath())) {
            $this->error(sprintf('Cannot delete theme %s', $this->argument('name')));
            return;
        }

        $this->info('done');
    }
}

