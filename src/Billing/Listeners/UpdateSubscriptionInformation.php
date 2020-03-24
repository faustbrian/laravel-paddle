<?php

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
