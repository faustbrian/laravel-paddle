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

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use KodeKeep\Paddle\Billing\Listeners\CreateSubscriptionInformation;
use KodeKeep\Paddle\Billing\Listeners\DeleteSubscriptionInformation;
use KodeKeep\Paddle\Billing\Listeners\UpdateSubscriptionInformation;
use KodeKeep\Paddle\Client;
use KodeKeep\Paddle\Webhooks\Events\PaymentDisputeCreated;
use KodeKeep\Paddle\Webhooks\Events\SubscriptionCancelled;
use KodeKeep\Paddle\Webhooks\Events\SubscriptionCreated;
use KodeKeep\Paddle\Webhooks\Events\SubscriptionPaymentRefunded;
use KodeKeep\Paddle\Webhooks\Events\SubscriptionUpdated;

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

        $this->registerEventListeners();
    }

    public function registerEventListeners(): void
    {
        Event::listen(SubscriptionCreated::class, CreateSubscriptionInformation::class);
        Event::listen(SubscriptionUpdated::class, UpdateSubscriptionInformation::class);
        Event::listen(SubscriptionCancelled::class, DeleteSubscriptionInformation::class);
        Event::listen(SubscriptionPaymentRefunded::class, DeleteSubscriptionInformation::class);
        Event::listen(PaymentDisputeCreated::class, DeleteSubscriptionInformation::class);
    }
}
