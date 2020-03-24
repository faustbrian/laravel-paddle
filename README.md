# Laravel Paddle

[![Latest Version](https://badgen.net/packagist/v/kodekeep/laravel-paddle)](https://packagist.org/packages/kodekeep/laravel-paddle)
[![Software License](https://badgen.net/packagist/license/kodekeep/laravel-paddle)](https://packagist.org/packages/kodekeep/laravel-paddle)
[![Build Status](https://img.shields.io/github/workflow/status/kodekeep/laravel-paddle/run-tests?label=tests)](https://github.com/kodekeep/laravel-paddle/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Coverage Status](https://badgen.net/codeclimate/coverage/kodekeep/laravel-paddle)](https://codeclimate.com/github/kodekeep/laravel-paddle)
[![Quality Score](https://badgen.net/codeclimate/maintainability/kodekeep/laravel-paddle)](https://codeclimate.com/github/kodekeep/laravel-paddle)
[![Total Downloads](https://badgen.net/packagist/dt/kodekeep/laravel-paddle)](https://packagist.org/packages/kodekeep/laravel-paddle)

This package was created by, and is maintained by [Brian Faust](https://github.com/faustbrian), and provides a Paddle integration for Laravel.

## Installation

```bash
composer require kodekeep/laravel-paddle
```

## Usage

See our [tests](https://github.com/kodekeep/laravel-paddle/tree/master/tests) for usage examples.

### `Billable`

If you are planning to use the `Billable` trait you'll need to use the following migration to add the necessary columns to the table of your billabled model.

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

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover a security vulnerability within this package, please send an e-mail to hello@kodekeep.com. All security vulnerabilities will be promptly addressed.

## Credits

This project exists thanks to all the people who [contribute](../../contributors).

## Support Us

We invest a lot of resources into creating and maintaining our packages. You can support us and the development through [GitHub Sponsors](https://github.com/sponsors/faustbrian).

## License

Laravel Paddle is an open-sourced software licensed under the [MPL-2.0](LICENSE.md).
