# Example

This document provides an example implementation of a subscription form with [Laravel Livewire](https://laravel-livewire.com/). **It is taken from a real application without any modification and should just serve as a starting point for your own integration.**

## Component

```php
<?php

namespace App\Http\Livewire;

use App\Actions\VerifyPlanEligibilityAction;
use App\SubscriptionPlan;
use Illuminate\View\View;
use KodeKeep\Livewired\Components\Concerns\InteractsWithTeam;
use KodeKeep\Livewired\Components\Concerns\InteractsWithUser;
use Livewire\Component;

class ManageSubscription extends Component
{
    use InteractsWithTeam;
    use InteractsWithUser;

    public ?string $selectedPlan = null;

    protected $listeners = ['refreshSubscriptionStatus' => '$refresh'];

    public function mount(): void
    {
        if ($this->team->subscribed()) {
            $this->selectedPlan = $this->team->subscriptionPlan()->id;
        }
    }

    public function subscribe(): void
    {
        abort_unless($this->user->ownsTeam($this->team), 403);

        try {
            $subscriptionPlan = SubscriptionPlan::findById($this->selectedPlan);

            VerifyPlanEligibilityAction::new($this->team)->execute($subscriptionPlan);

            if ($this->team->subscribed()) {
                $this->team->swapPlan($subscriptionPlan->id);

                flash()->success('Your subscription plan has been updated.');
            } else {
                $this->emit('openCheckout', $subscriptionPlan->id);
            }
        } catch (\Throwable $th) {
            flash()->error($th->getMessage());
        }
    }

    public function cancelSubscription(): void
    {
        $this->team->cancelNow();
    }

    public function getPassthroughProperty(): string
    {
        return encrypt(json_encode([
            'app'     => config('paddle.vendor_app'),
            'modelId' => $this->team->id,
        ]));
    }

    public function render(): View
    {
        return view('livewire.manage-subscription', [
            'plansMonthly'  => SubscriptionPlan::monthly(),
            'plansYearly'   => SubscriptionPlan::yearly(),
            'isSubscribed'  => $this->team->subscribed(),
            'onTrial'       => $this->team->onTrial(),
            'onGracePeriod' => $this->team->onGracePeriod(),
        ]);
    }
}
```

## View

```blade
<x-card :title="trans('app.manage_subscription.title')" :description="trans('app.manage_subscription.description')">
    <form action="#" method="POST">
        <div class="overflow-hidden">
            @include('shared.flash')

            @if($onGracePeriod)
                <div class="mb-6 alert-info">
                    You have cancelled your subscription. The benefits of your subscription will continue until your current billing period ends on <strong>{{ $this->team->grace_period_ends_at->toFormattedDateString() }}</strong>.
                </div>
            @endif

            <fieldset>
                <legend class="text-base font-medium leading-6 text-gray-900">Monthly Plans</legend>
                <p class="text-sm leading-5 text-gray-500">Simple month-to-month pricing.</p>

                <div class="mt-4 spaced-y-4">
                    @foreach($plansMonthly as $plan)
                        <div class="flex items-center">
                            <input id="plan_monthly_{{ $plan->id }}" value="{{ $plan->id }}" wire:model="selectedPlan" type="radio" class="w-4 h-4 text-blue-600 transition duration-150 ease-in-out form-radio" />

                            <label for="plan_monthly_{{ $plan->id }}" class="ml-3">
                                <span class="block text-sm font-medium leading-5 text-gray-700">{{ $plan->name }} ({{ Money::format($plan->price) }} per month)</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="mt-6">
                <legend class="text-base font-medium leading-6 text-gray-900">Yearly Plans</legend>
                <p class="text-sm leading-5 text-gray-500">Save 20% and simplify your bookkeeping by paying annually.</p>

                <div class="mt-4 spaced-y-4">
                    @foreach($plansYearly as $plan)
                        <div class="flex items-center">
                            <input id="plan_yearly_{{ $plan->id }}" value="{{ $plan->id }}" wire:model="selectedPlan" type="radio" class="w-4 h-4 text-blue-600 transition duration-150 ease-in-out form-radio" />

                            <label for="plan_yearly_{{ $plan->id }}" class="ml-3">
                                <span class="block text-sm font-medium leading-5 text-gray-700">{{ $plan->name }} ({{ Money::format($plan->price) }} per year)</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        </div>

        <div class="flex justify-end mt-6">
            @if($isSubscribed && !$onGracePeriod)
                <x-card-divider />

                <button type="button" class="relative inline-flex items-center px-4 py-2 mr-3 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:shadow-outline-red focus:border-red-700 active:bg-red-700" wire:click="cancelSubscription">
                    Cancel Subscription
                </button>
            @endif

            <button type="button" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:bg-blue-500 active:bg-blue-600" wire:click="subscribe">
                Subscribe
            </button>
        </div>
    </form>
</x-card>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.livewire.on('openCheckout', product => Paddle.Checkout.open({
            product,
            email: '{{ $this->team->email }}',
            passthrough: '{{ $this->passthrough }}',
            successCallback (data) {
                alert('Thanks for your purchase.');

                window.location.reload();
            },
            closeCallback (data) {
                alert('Your purchase has been cancelled, we hope to see you again soon!');

                window.location.reload();
            }
        }));
    });
</script>
```
