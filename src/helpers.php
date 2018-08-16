<?php

if (! function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Nano7\View\View|\Nano7\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}

if (! function_exists('frames')) {
    /**
     * @param null $frame
     * @param array $payload
     * @param bool $echo
     * @param bool $separator
     * @return \Nano7\View\Frames\Frames|array|null
     */
    function frames($frame = null, $payload = [], $echo = false, $separator = null)
    {
        $frames = app('frames');

        if (is_null($frame)) {
            return $frames;
        }

        return $frames->render($frame, $payload, $echo, $separator);
    }
}