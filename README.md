# Theme Management for Laravel

Laravel-Theme is a theme management for Laravel 5+, it is the easiest way to organize your skins, layouts and assets.

## Usage

Theme has many features to help you get started with Laravel

- [Installation](#installation)
- [Create new theme](#create-new-theme)
- [Basic usage](#basic-usage)
- [Custom boilerplate](#custom-theme-boilerplate)



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
You can use it in your routes as a middleware
```php
Route::get('/', function () {
    //
})->middleware('theme:yourtheme');
```
		
You can set theme manually:

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

### Custom Helper functions
@themeInclude is a blade directive for including sup views eg:
~~~ 
## insted of 
@include('someview', ['somedata'=>$var])

## use theme campatible
@themeInclude('someview', ['somedata'=>$var] )
~~~

@themeFirst is blade directive instead of @includeFirst
~~~ 
## insted of 
@includeFirst(['someview','view-other'], ['somedata'=>$var] )

## use theme campatible
@themeFirst(['someview','view-other'], ['somedata'=>$var] )
~~~

Package will publish assets folder to public folder as indicated on config file theme.php  
In order to get url for current theme use  theme_uri()
``` 
<link href="{{ theme_uri() }}/css/style.css" rel="stylesheet" />

<script src="{{ theme_uri() }}/js/script.js"></script>

<img src="{{ theme_uri() }}/image/img.png" />

```

Please place all your assets to assets folder and  Correctly change config/theme.php file

## Custom theme boilerplate
if you want to change boilerplate you can use 
~~~
php artisan vendor:publish --provider="JascoB\Theme\Providers\ThemeServiceProvider" --tag="template"
~~~
It will create a fonder on ``resources/views/vendor/theme/template``
you can make boilerplate there and The package will copy the template from there

## Tests
In order to run test
~~~
php vendor/bin/phpunit tests
~~~
