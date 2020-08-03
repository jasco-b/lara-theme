<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 18:28
 */

namespace JascoB\Theme\Contracts;

interface IThemeLoader
{
    /**
     * @return array
     */
    public function loadAll();
}
