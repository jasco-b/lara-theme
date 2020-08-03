<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-31
 * Time: 20:32
 */

namespace JascoB\Theme\Traits;


use JascoB\Theme\Contracts\IThemeConfig;

trait ConfigFileModifier
{
    public function modifyConfig($path, $name, IThemeConfig $config)
    {
        $file = $path . DIRECTORY_SEPARATOR . $config->configFile();
        $config = json_decode(file_get_contents($file), 1);

        $config['name'] = $name;

        return file_put_contents($file, json_encode($config));
    }
}
