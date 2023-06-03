<?php

namespace App\Providers;

use App\Shortcodes\AssetShortcode;
use App\Shortcodes\BoldShortcode;
use App\Shortcodes\ComponentShortcode;
use App\Shortcodes\UrlShortcode;
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
    Shortcode::register('b', BoldShortcode::class);
    Shortcode::register('asset', AssetShortcode::class);
    Shortcode::register('url', UrlShortcode::class);
    Shortcode::register('component',ComponentShortcode::class);
  }
}
