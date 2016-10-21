<?php

namespace Wisdom\Openform;

use Illuminate\Support\ServiceProvider;

class OpenformServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    $this->publishes([
        __DIR__.'/views' => base_path('resources/views/openform/'),
        __DIR__.'/assets' => public_path('openform/assets'),
//        __DIR__.'/Controllers' => base_path('app/Http/Controllers'),
//        __DIR__.'/Models' => base_path('app/Http/Models'),
        __DIR__.'/images/openform-images' => public_path('/openform-images'),
        __DIR__.'/migrations' => base_path('database/migrations'),

    ]);
        include __DIR__.'/routes/web.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Wisdom\Openform\Controllers\ApiOpenFormController');
        $this->app->make('Wisdom\Openform\Controllers\OpenFormController');
    }
}
