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
use Exception;
use KodeKeep\Paddle\Webhooks\Events\SubscriptionCreated;

class CreateSubscriptionInformation extends Listener
{
    public function handle(SubscriptionCreated $event)
    {
        // Decrypt and parse the passthrough...
        $passthrough = \json_decode(decrypt($event->passthrough), true, 512, \JSON_THROW_ON_ERROR);

        if ($passthrough['app'] !== config('paddle.vendor_app')) {
            throw new Exception('Invalid application in passthrough.');
        }

        // Find the model that belongs to this subscription...
        $model = $this->getModel()->findOrFail($passthrough['modelId']);

        // Store the Paddle IDs for later usage...
        $model->forceFill([
            'paddle_id'                   => $event->user_id,
            'paddle_subscription_id'      => $event->subscription_id,
            'paddle_subscription_plan_id' => $event->subscription_plan_id,
            'trial_ends_at'               => null,
            'grace_period_ends_at'        => null,
        ])->save();

        // If we received a trialing subscription we want to set the end date...
        if ($event->status === 'trialing') {
            $model->forceFill([
                'trial_ends_at' => Carbon::parse($event->next_bill_date),
            ])->save();
        }
    }
}
