<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 18:30
 */

namespace JascoB\Theme\Contracts;

use JascoB\Theme\VO\ThemeVo;

interface IAssetPublisher
{
    public function publish(ThemeVo $theme);
}
