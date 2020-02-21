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

];
