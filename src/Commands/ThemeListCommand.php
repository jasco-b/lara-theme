<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:14
 */

namespace JascoB\Theme\Commands;


use Illuminate\Console\Command;
use JascoB\Theme\Facades\Theme;

class ThemeListCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available themes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $themes = Theme::all();

        $themeArray = array_map(function ($theme) {
            return [
                'name' => $theme->getName(),
                'description' => $theme->getDescription(),
                'path' => $theme->getPath(),
            ];
        }, $themes);

        $headers = [
            'name', 'description', 'path',
        ];

        $this->table($headers, $themeArray);
    }
}
