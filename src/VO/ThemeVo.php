<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 13:01
 */

namespace JascoB\Theme\VO;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JascoB\Theme\Exceptions\DomainException;
use JascoB\Theme\Traits\LoadTrait;

class ThemeVo implements Arrayable
{
    use LoadTrait;

    private $name;

    /**
     * @var null
     */
    private $description;

    /**
     * @var null
     */
    private $parent;
    /**
     * @var null
     */
    private $path;

    private $public_url;

    public function __construct($name, $path, $public_url, $options = [])
    {
        $this->throwIfBlank($name);
        $this->throwIfBlank($path);
        $this->throwIfBlank($public_url);

        $this->load($options);

        $this->name = $name;
        $this->path = $path;

        $this->public_url = $public_url;
    }

    /**
     * @param $value
     * @param null $filed
     * @throws DomainException
     */
    private function throwIfBlank($value, $filed = null)
    {
        $filed = $filed ?: "variable";

        if (trim($value) === '') {
            throw new DomainException("Theme $filed not provided");
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return null
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getUrl()
    {
        return $this->public_url . '/' . $this->getThemeNamespace();
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getThemeNamespace()
    {
        return Str::slug('theme-' . $this->getName());
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'parent' => $this->getParent(),
            'path' => $this->getPath(),
        ];
    }
}
