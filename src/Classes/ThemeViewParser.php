<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 16:05
 */

namespace JascoB\Theme\Classes;


use Illuminate\View\ViewFinderInterface;
use JascoB\Theme\Contracts\IThemeViewParser;

class ThemeViewParser implements IThemeViewParser
{

    public function hasNamespace($name)
    {
        return strpos($name, ViewFinderInterface::HINT_PATH_DELIMITER) !== false;
    }

    public function parseName($name)
    {
        if (!$this->hasNamespace($name)) {
            return [null, $name];
        }

        return explode(ViewFinderInterface::HINT_PATH_DELIMITER, $name);
    }
}
