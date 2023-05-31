<?php

namespace App\Shortcodes;

class UrlShortcode
{
    public function register($shortcode, $content, $compiler, $name, $viewData): string
    {
        return url($content);
    }
}
