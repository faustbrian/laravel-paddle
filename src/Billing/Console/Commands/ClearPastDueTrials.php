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

namespace KodeKeep\Paddle\Billing\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearPastDueTrials extends Command
{
    protected $signature = 'clear:trials';

    protected $description = 'Reset the trial ending dates of all .';

    public function handle()
    {
        $model = config('paddle.model');

        $model::whereNotNull('trial_ends_at')->each(function ($team) {
            if (Carbon::now()->isPast($team->trial_ends_at)) {
                $team->forceFill(['trial_ends_at' => null])->save();
            }
        });
    }
}
