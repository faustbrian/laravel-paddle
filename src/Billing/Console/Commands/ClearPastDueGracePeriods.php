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

class ClearPastDueGracePeriods extends Command
{
    protected $signature = 'clear:grace-periods';

    protected $description = 'Reset the grace period ending dates of all models.';

    public function handle()
    {
        $model = config('paddle.model');

        $model::whereNotNull('grace_period_ends_at')->each(function ($team) {
            if (Carbon::now()->isPast($team->grace_period_ends_at)) {
                $team->forceFill([
                    'paddle_subscription_id'      => null,
                    'paddle_subscription_plan_id' => null,
                    'trial_ends_at'               => null,
                    'grace_period_ends_at'        => null,
                ])->save();
            }
        });
    }
}
