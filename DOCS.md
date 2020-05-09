# Documentation

This package is a wrapper around https://github.com/kodekeep/paddle-sdk that provides an easy integration into Laravel applications that require billing through Paddle.

The Paddle API documentation can be found at https://paddle.com/docs/.

## Configuration

### Webhooks

First you will need to go to https://vendors.paddle.com/alerts-webhooks and configure your webhook URL. This should point to `https://domain.com/paddle/webhook` with webhook alerts for all `Subscriptions` and `Risk & disputes`.

Next you will need to grab the values for `PADDLE_VENDOR_ID` and `PADDLE_VENDOR_AUTH_CODE` from https://vendors.paddle.com/authentication. Finally you will need to grab the value for `PADDLE_VENDOR_PUBLIC_KEY` from https://vendors.paddle.com/public-key.

Once everything is put together your `.env` file should contain something like the below.

```ini
PADDLE_VENDOR_ID=123456789
PADDLE_VENDOR_AUTH_CODE=somelonghashforauthentication
PADDLE_VENDOR_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
supersecretvalueofyourpublickey
-----END PUBLIC KEY-----"
```

### Vendor Application Identifier

The `PADDLE_VENDOR_APP` environment variable is not directly used by Paddle but a value for internal purposes to separate your application data. You might have multiple applications and subscriptions on a single Paddle account to keep your accounting simpler but Paddle itself has no easy way of separating and filter data based on the origin.

For example you could have 5 SaaS applications and you have a customer with the email `john@doe.com` on all of them. Now if you would list invoices from Paddle you could end up with mixed up invoices from multiple applications. To solve this issue you can use the `PADDLE_VENDOR_APP` environment variable and set it to something like `PADDLE_VENDOR_APP=myapp`. This will ensure that listed invoices and incoming webhook data belong to the correct application.

## Webhooks

When an event occurs for your Paddle account an event will be triggered for which you will receive a payload to a configured URL via POST. By default this will cause an error on your end because Laravel expects POST requests to contain a CSRF token, which Paddle won't be able to send.

The solution to this problem is to add the `paddle/webhook` path to the `$except` array of the `VerifyCsrfToken` class in your project. This will ensure that Paddle requests go through and are processed, assuming you configured the correct Paddle authentication code.

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'paddle/webhook',
    ];
}
```

## Client

As previously mentioned, this package is a wrapper around https://github.com/kodekeep/paddle-sdk which is a package that provides an API Client and Webhook handling in PHP for the Paddle API.

This package creates a binding of that client which can be accessed via container resolution `resolve('paddle')` or a Facade `KodeKeep\Paddle\Facades\Paddle::someMethod()`.

## Billable

### Migration

If you are planning to use the `Billable` trait you'll need to use the following migration to add the necessary columns to the table of your billable model.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class AddPaddleToUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('paddle_id')->nullable()->index();
            $table->unsignedBigInteger('paddle_subscription_id')->nullable()->index();
            $table->unsignedBigInteger('paddle_subscription_plan_id')->nullable()->index();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('grace_period_ends_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paddle_id');
            $table->dropColumn('paddle_subscription_id');
            $table->dropColumn('paddle_subscription_plan_id');
            $table->dropColumn('trial_ends_at');
            $table->dropColumn('grace_period_ends_at');
        });
    }
}
```

### Get the currently active subscription

```php
$user->subscription();
```

### Swap to another subscription plan

```php
$user->swapPlan(123456);
```

### Immediately cancel the currently active subscription

```php
$user->cancelNow();
```

### List all invoices that match the current application

```php
$user->invoices();
```

### Check if the model is currently on trial

```php
$user->onTrial();
```

### Check if the model is currently on its grace period

```php
$user->onGracePeriod();
```

### Check if the model is currently subscribed to any plan

```php
$user->subscribed();
```

### Check if the model is currently subscribed to a specific plan

```php
$user->subscribedToPlan(123456);
```

### Check if the model has a paddle ID

```php
$user->hasPaddleId();
```

### Check if the mdoel has a paddle subscription ID

```php
$user->hasPaddleSubscriptionId();
```

### Check if the model has a paddle subscription plan ID

```php
$user->hasPaddleSubscriptionPlanId();
```
