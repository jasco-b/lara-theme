<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:48
 */

namespace JascoB\Theme\Traits;


use Illuminate\Support\Arr;

trait GetArrayValueTrait
{
    protected function getValue($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }

    protected function hasValue($array, $key)
    {
        return Arr::has($array, $key);
    }
}
