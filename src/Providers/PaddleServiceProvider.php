<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Paddle.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\Paddle\Providers;

use Illuminate\Support\ServiceProvider;
use KodeKeep\Paddle\Client;

class PaddleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/paddle.php', 'paddle');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/paddle.php' => $this->app->configPath('paddle.php'),
            ], 'config');
        }

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        $this->app->singleton('paddle', function ($app) {
            $config = $app['config']['paddle'];

            return new Client($config['vendor_id'], $config['vendor_auth_code']);
        });
    }
}
