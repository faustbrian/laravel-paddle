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

namespace KodeKeep\Paddle\Billing\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use KodeKeep\Paddle\Billing\PaddleInvoice;
use KodeKeep\Paddle\Facades\Paddle;

trait Billable
{
    public function subscription(): array
    {
        return Paddle::product()->transactions()->all('subscription', $this->paddle_subscription_id)[0];
    }

    public function swapPlan(int $planId): void
    {
        Paddle::subscription()->subscriptionUsers()->update([
            'subscription_id' => $this->paddle_subscription_id,
            'plan_id'         => $planId,
        ]);
    }

    public function cancelNow(): void
    {
        Paddle::subscription()->subscriptionUsers()->cancel([
            'subscription_id' => $this->paddle_subscription_id,
        ]);
    }

    public function invoices(): Collection
    {
        if (! $this->subscribed()) {
            return collect();
        }

        $transactions = Paddle::product()->transactions()->all('user', $this->paddle_id);

        return collect($transactions)->filter(function ($transaction) {
            try {
                $passthrough = \json_decode(decrypt($transaction['passthrough']), true, 512, \JSON_THROW_ON_ERROR);

                return $passthrough['app'] === config('vendor_app');
            } catch (\Throwable $th) {
                return false;
            }
        })->map(fn ($transaction) => new PaddleInvoice($transaction));
    }

    public function onTrial(): bool
    {
        $date = $this->trial_ends_at;

        if (empty($date)) {
            return false;
        }

        return Carbon::parse($date)->isFuture();
    }

    public function onGracePeriod(): bool
    {
        $date = $this->grace_period_ends_at;

        if (empty($date)) {
            return false;
        }

        return Carbon::parse($date)->isFuture();
    }

    public function subscribed(): bool
    {
        return ! empty($this->paddle_subscription_id);
    }

    public function subscribedToPlan(int $plan): bool
    {
        return $this->paddle_subscription_plan_id === $plan;
    }

    public function hasPaddleId(): bool
    {
        return ! is_null($this->paddle_id);
    }

    public function hasPaddleSubscriptionId(): bool
    {
        return ! is_null($this->paddle_subscription_id);
    }

    public function hasPaddleSubscriptionPlanId(): bool
    {
        return ! is_null($this->paddle_subscription_plan_id);
    }
}
