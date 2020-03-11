<?php

namespace Zdk\Cors;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Zdk\Cors\Services\CorsService;

/**
 *  跨域serviceProvider
 * Class CorsServiceProvider
 * @package Zdk\Cors
 */
class ServiceProvider extends LaravelServiceProvider
{

    public function register()
    {
        $this->app->singleton(CorsService::class, function ($app) {
            $config = $app['config']->get('cors');

            $options = [
                'allowOrigins' => $config['allow_origin'],
                'allowMethods' => $config['allow_methods'],
                'allowHeaders' => $config['allow_headers']
            ];

            return new CorsService($options);
        });
    }

    public function boot()
    {
        $this->publishes([
            $this->configPath() => config_path('cors.php')
        ]);
    }

    private function configPath()
    {
        return __DIR__.'/config/cors.php';
    }
}