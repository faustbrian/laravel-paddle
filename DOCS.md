# Documentation

This package is a wrapper around https://github.com/kodekeep/paddle-sdk that provides an easy integration into Laravel applications that require billing through Paddle.

The Paddle API documentation can be found at https://paddle.com/docs/.

## Configuration

...

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

...

```php
...
```

### Swap to another subscription plan

...

```php
...
```

### Cancel the currently active subscription

...

```php
...
```

### List all invoices that match the current application

...

```php
...
```

### Check if the model is currently on trial

...

```php
...
```

### Check if the model is currently on its grace period

...

```php
...
```

### Check if the model is currently subscribed to any plan

...

```php
...
```

### Check if the model is currently subscribed to a specific plan

...

```php
...
```

### Check if the model has a paddle ID

...

```php
...
```

### Check if the mdoel has a paddle subscription ID

...

```php
...
```

### Check if the model has a paddle subscription plan ID

...

```php
...
```
