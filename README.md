# Theme Management for Laravel

Laravel-Theme is a theme management for Laravel 5+, it is the easiest way to organize your skins, layouts and assets.

## Usage

Theme has many features to help you get started with Laravel

- [Installation](#installation)
- [Create new theme](#create-new-theme)
- [Basic usage](#basic-usage)



## Installation


~~~php
'providers' => [
	...
	 \JascoB\Theme\Providers\ThemeServiceProvider::class,

]
~~~

Theme also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `config/app.php` file.

~~~php
'aliases' => [
	...
	'Theme'=> \JascoB\Theme\Facades\Theme::class,

]
~~~
Publish config using artisan CLI.

~~~
php artisan vendor:publish --provider="JascoB\Theme\Providers\ThemeServiceProvider"
~~~

## Create new theme

The first time you have to create theme "default" structure, using the artisan command:

~~~
php artisan theme:create default
~~~


This will create the following directory structure:

```
├── public/
    └── themes/
	    └── default/
		    ├── assets
        	    └── config.json
```

To delete an existing theme, use the command:

~~~
php artisan theme:delete default
~~~

If you want to list all installed themes use the command:

~~~
php artisan theme:list
~~~

You can duplicate an existing theme:
~~~
php artisan theme:duplicate name new-theme
~~~



Create from the application without CLI.

~~~php
Artisan::call('theme:create', ['name' => 'foo']);
~~~

## Basic usage

To display a view from the controller:

~~~php
namespace App\Http\Controllers;

use Theme;

class HomeController extends Controller {

	public function getIndex()
	{
		return Theme::view('index');
	}
	...
}
~~~
>This will use the theme and layout set by default on `.env`

		
You can set theme:

~~~php
...		
Theme::set('themename');
        
return Theme::view('index');
...
~~~

To check whether a theme exists.

~~~php
Theme::has('themename');
~~~

Each theme must come supplied with a manifest file `config.json` stored at the root of the theme, which defines supplemental details about the theme. 
~~~json
{
    "name": "Default"
}
~~~

## Custom theme boilerplate
if you want to change boilerplate you can use 
~~~
php artisan vendor:publish --provider="JascoB\Theme\Providers\ThemeServiceProvider" --tag="template"
~~~
