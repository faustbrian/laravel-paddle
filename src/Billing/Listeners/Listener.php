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

namespace KodeKeep\Paddle\Billing\Listeners;

use Illuminate\Database\Eloquent\Model;

class Listener
{
    protected function getModel(): Model
    {
        $model = config('paddle.model');

        return new $model();
    }
}
