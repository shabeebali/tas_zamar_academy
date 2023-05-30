<?php

namespace App\View\Components\Frontend;

use App\Models\Settings;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $content = Settings::getByKey('footer_content','');

        return view('frontend.footer',['content' => $content]);
    }
}
