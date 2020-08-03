<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 18:28
 */

namespace JascoB\Theme\Contracts;

interface IThemeViewParser
{
    public function hasNamespace($name);

    public function parseName($name);
}
