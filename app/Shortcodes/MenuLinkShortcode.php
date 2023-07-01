<?php

namespace App\Shortcodes;

use Illuminate\Support\Facades\URL;

class MenuLinkShortcode
{
    public function register($shortcode, $content, $compiler, $name, $viewData): string
    {
        $baseClass = '';
        if(URL::current() == URL::to($shortcode->to)) {
            $baseClass.= 'current';
        }
        $str = '<li class="'.$baseClass.'">';
        $str .= '<a href="'.url($shortcode->to).'">';
        $str .= $content.'</a></li>';
        return $str;
    }
}
