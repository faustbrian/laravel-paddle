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

use Carbon\Carbon;
use KodeKeep\Paddle\Webhooks\Events\SubscriptionCancelled;

class DeleteSubscriptionInformation extends Listener
{
    public function handle(SubscriptionCancelled $event)
    {
        // Find the model that belongs to this subscription...
        $model = $this->getModel()->where('paddle_id', $event->user_id)->firstOrFail();

        // Store the Paddle IDs for later usage...
        $model->forceFill([
            'paddle_id'                   => null,
            'paddle_subscription_id'      => null,
            'paddle_subscription_plan_id' => null,
            'trial_ends_at'               => null,
        ])->save();

        // If we received a trialing subscription we want to set the end date...
        $model->forceFill([
            'grace_period_ends_at' => Carbon::parse($event->cancellation_effective_date),
        ])->save();
    }
}
