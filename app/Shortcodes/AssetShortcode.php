<?php

namespace App\Shortcodes;

class AssetShortcode
{
    public function register($shortcode, $content, $compiler, $name, $viewData): string
    {
        return asset($content);
    }
}
