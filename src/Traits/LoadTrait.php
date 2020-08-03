<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-15
 * Time: 10:38
 */

namespace JascoB\Theme\Traits;

trait LoadTrait
{
    public function load($data)
    {
        $ref = new \ReflectionClass($this);
        $items = $ref->getProperties();

        $classPublicAttributes = array_map(function ($data) {
            return $data->name;
        }, $items);

        foreach ($data as $attr => $value) {
            if (in_array($attr, $classPublicAttributes)) {
                $this->{$attr} = $value;
            }
        }
    }
}
