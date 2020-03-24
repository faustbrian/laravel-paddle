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

use KodeKeep\Paddle\Webhooks\Events\SubscriptionUpdated;

class UpdateSubscriptionInformation extends Listener
{
    public function handle(SubscriptionUpdated $event)
    {
        // Find the model that belongs to this subscription...
        $model = $this->getModel()->where('paddle_id', $event->user_id)->firstOrFail();

        // Store the Paddle IDs for later usage...
        $model->forceFill([
            'paddle_id'                   => $event->user_id,
            'paddle_subscription_id'      => $event->subscription_id,
            'paddle_subscription_plan_id' => $event->subscription_plan_id,
            'trial_ends_at'               => null,
            'grace_period_ends_at'        => null,
        ])->save();
    }
}
