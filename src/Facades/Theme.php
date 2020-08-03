<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:16
 */

namespace JascoB\Theme\Facades;


use Illuminate\Support\Facades\Facade;

class Theme extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Jasco-theme';
    }
}
