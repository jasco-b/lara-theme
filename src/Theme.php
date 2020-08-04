<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 16:59
 */

namespace JascoB\Theme;


use Illuminate\Support\Arr;
use Illuminate\View\Factory;
use JascoB\Theme\Classes\AssetPublisher;
use JascoB\Theme\Classes\ThemeConfig;
use JascoB\Theme\Classes\ThemeViewParser;
use JascoB\Theme\Contracts\IAssetPublisher;
use JascoB\Theme\Contracts\ITheme;
use JascoB\Theme\Contracts\IThemeConfig;
use JascoB\Theme\Contracts\IThemeLoader;
use JascoB\Theme\Contracts\IThemeViewParser;
use JascoB\Theme\Exceptions\ThemeNotFoundException;
use JascoB\Theme\Exceptions\ViewNotFoundException;
use JascoB\Theme\Traits\GetArrayValueTrait;
use JascoB\Theme\VO\ThemeVo;

class Theme implements ITheme
{
    use  GetArrayValueTrait;

    /**
     * @var ThemeVo
     */
    protected $current;

    /**
     * @var array Theme list
     */
    protected $list;

    /**
     * @var ThemeConfig
     */
    private $config;

    /**
     * @var IThemeLoader
     */
    private $themeLoader;

    /**
     * @var Factory
     */
    private $view;

    /**
     * @var ThemeViewParser
     */
    private $parser;

    /**
     * @var AssetPublisher
     */
    private $publisher;

    public function __construct(
        Factory $view,
        IThemeConfig $config,
        IThemeLoader $themeLoader,
        IThemeViewParser $parser,
        IAssetPublisher $publisher)
    {
        $this->view = $view;
        $this->config = $config;
        $this->themeLoader = $themeLoader;
        $this->parser = $parser;
        $this->publisher = $publisher;

        $this->loadThemes();
    }

    /**
     * @param $theme
     * @return $this
     * @throws Exceptions\DomainException
     * @throws ThemeNotFoundException
     */
    public function set($theme)
    {
        if (!$this->has($theme)) {
            throw new ThemeNotFoundException("$theme theme not found");
        }

        $this->current = $this->get($theme);

        if ($this->config->shouldReplaceView()) {
            $this->addLocation($this->current);
        }

        $this->addNamespaces($this->current);

        $this->publishAssets($this->current);

        $this->publisher->publish($this->current);

        return $this;
    }

    public function has($theme)
    {
        return $this->hasValue($this->list(), $theme);
    }

    /**
     * @param null $theme
     * @return ThemeVo|null
     */
    public function get($theme = null)
    {
        if ($theme === null && $this->current) {
            return $this->get($this->current->getName());
        }

        if (!$this->has($theme)) {
            return null;
        }

        return $this->getValue($this->list(), $theme);
    }

    protected function addLocation(ThemeVo $theme)
    {
        if (method_exists($this->view, 'getFinder')) {
            $this->view->getFinder()->prependLocation($theme->getPath());
        }
    }

    protected function addNamespaces(ThemeVo $theme)
    {
        $this->view->addNamespace($theme->getThemeNamespace(), $theme->getPath());
    }

    private function publishAssets(ThemeVo $current)
    {
        $this->publisher->publish($current);
    }

    public function all()
    {
        return $this->list();
    }

    public function list()
    {
        if ($this->list === null) {
            $this->loadThemes();
        }

        return $this->list;
    }

    protected function loadThemes()
    {
        $this->list = $this->themeLoader->loadAll();
        return $this->list;
    }

    public function info($field = 'name')
    {
        if ($this->current && method_exists($this->current, $field)) {
            return $this->current->{$field}();
        }

        return null;
    }


    /**
     * Get the first view that actually exists from the given list.
     *
     * @param  array $views
     * @param  \Illuminate\Contracts\Support\Arrayable|array $data
     * @param  array $mergeData
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \InvalidArgumentException
     */
    public function first(array $views, $data = [], $mergeData = [])
    {
        $view = Arr::first($views, function ($view) {
            return $this->exists($view);
        });

        if (!$view) {
            throw new ViewNotFoundException('None of the views in the given array exist.');
        }

        return $this->make($view, $data, $mergeData);
    }

    /**
     * Get the rendered content of the view based on a given condition.
     *
     * @param  bool $condition
     * @param  string $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array $data
     * @param  array $mergeData
     * @return string
     */
    public function renderWhen($condition, $view, $data = [], $mergeData = [])
    {
        if (!$condition) {
            return null;
        }

        return $this->view->make($view, $data, $mergeData);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array $data
     * @param  array $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = [])
    {
        if ($this->current && !$this->config->shouldReplaceView()) {
            return $this->makeWithThemeNamespace($view, $data, $mergeData);
        }

        return $this->view->make($view, $data, $mergeData);
    }

    public function view($view, $data = [], $mergeData = [])
    {
        return $this->make($view, $data, $mergeData);
    }

    public function include($view, $data = [], $mergeData = [])
    {
        return $this->view->make($view, $data, $mergeData);
    }

    public function exists($view, $nameSpace = true)
    {
        if ($nameSpace && $this->current && !$this->config->shouldReplaceView()) {
            return $this->existsWithThemeSpaces($view);
        }

        return $this->view->exists($view);
    }

    /**
     * @return ThemeViewParser
     */
    public function parser()
    {
        return $this->parser;
    }

    protected function makeWithThemeNamespace($view, $data = [], $mergeData = [])
    {
        $viewList = $this->resloveViewNames($view);

        return $this->view->first($viewList, $data, $mergeData);
    }

    protected function existsWithThemeSpaces($view)
    {
        $viewList = $this->resloveViewNames($view);

        return Arr::first($viewList, function ($view) {
            return $this->exists($view, false);
        });
    }

    public function resloveViewNames($view)
    {
        [$namespace, $name] = $this->parser()->parseName($view);

        $themePath = $this->current->getThemeNamespace() . '::' . $name;

        return [$themePath, $view];
    }

    /**
     * @return Factory
     */
    public function getViewFactory()
    {
        return $this->view;
    }

}
