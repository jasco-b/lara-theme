<?php

return [
    /*
	|--------------------------------------------------------------------------
	| Active theme
	|--------------------------------------------------------------------------
	|
	| Active theme
	| eg. test
	|
	*/
    'active' => '',


    /*
	|--------------------------------------------------------------------------
	| Theme path
	|--------------------------------------------------------------------------
	|
	| This is the path where Themes located
	| eg. resources/views/themes
	|
	*/
    'theme_path' => base_path(env('THEME_PATH', 'resources/views/themes')),


    /*
	|--------------------------------------------------------------------------
	| Public asset Path
	|--------------------------------------------------------------------------
	|
	| This is public asset path where we need to publish or link asset folders
	| eg. public/assets
	|
	*/
    'public_asset_path' => public_path(env('THEME_ASSET_PATH', 'assets')),

    /*
	|--------------------------------------------------------------------------
	| Public Asset Uri
	|--------------------------------------------------------------------------
	|
	| Public asset URL in order to resolve
	| eg. /assets
	|
	*/
    'public_asset_uri' => env('THEME_ASSET_URI', 'assets'),


    /*
	|--------------------------------------------------------------------------
	| Symlink
	|--------------------------------------------------------------------------
	|
	| Should we symlink assets folder or just publish it
	|
	|
	*/
    'symlink' => env('THEME_SYMLINK', true),


    /*
    |--------------------------------------------------------------------------
    | Config file name
    |--------------------------------------------------------------------------
    |
    | configuration file name where we read name of the config
    |
    */
    'config' => [
        'name' => env('THEME_CONFIG', 'config.json'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Folders
    |--------------------------------------------------------------------------
    |
    | asset folder name for publish
    |
    */
    'folders' => [
        'assets' => 'assets',
    ],

];
