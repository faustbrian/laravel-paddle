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

return [

    /*
    |--------------------------------------------------------------------------
    | Default Vendor ID
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'vendor_id' => env('PADDLE_VENDOR_ID'),

    /*
    |--------------------------------------------------------------------------
    | Default Vendor Authentication Code
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'vendor_auth_code' => env('PADDLE_VENDOR_AUTH_CODE'),

    /*
    |--------------------------------------------------------------------------
    | Default Vendor Public Key
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'vendor_public_key' => env('PADDLE_VENDOR_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Vendor Public Key
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'vendor_app' => env('PADDLE_VENDOR_APP'),

    /*
    |--------------------------------------------------------------------------
    | Paddle Model
    |--------------------------------------------------------------------------
    |
    | This is the model in your application that implements the Billable trait
    | provided by Paddle. It will serve as the primary model you use while
    | interacting with Paddle related methods, subscriptions, and so on.
    |
    */

    'model' => env('PADDLE_MODEL', App\Models\User::class),

    /*
    |--------------------------------------------------------------------------
    | Currency Locale
    |--------------------------------------------------------------------------
    |
    | This is the default locale in which your money values are formatted in
    | for display. To utilize other locales besides the default en locale
    | verify you have the "intl" PHP extension installed on the system.
    |
    */

    'currency_locale' => env('PADDLE_CURRENCY_LOCALE', 'en'),

];
