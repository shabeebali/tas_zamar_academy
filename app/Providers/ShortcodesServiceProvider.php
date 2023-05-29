<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Webwizo\Shortcodes\Facades\Shortcode;

class ShortcodesServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    //Shortcode::register('b', BoldShortcode::class);
    //Shortcode::register('i', 'App\Shortcodes\ItalicShortcode@custom');
  }
}
