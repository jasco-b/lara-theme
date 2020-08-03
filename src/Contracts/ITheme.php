<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-29
 * Time: 17:15
 */

namespace JascoB\Theme\Contracts;


interface ITheme
{
    public function set($theme);

    public function all();

    public function get($theme = null);

    public function info($theme = null);

    public function has($theme);

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = []);

}
