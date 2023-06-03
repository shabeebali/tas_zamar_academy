<?php

namespace App\Shortcodes;

class ComponentShortcode
{
    public function register($shortcode, $content, $compiler, $name, $viewData): string
    {
        $class = '\App\View\Components\Frontend\\'.$shortcode->name;
        $obj = new $class;
        return $obj->render()->with(['data' => $shortcode->toArray()]);
    }
}
