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

namespace KodeKeep\Paddle\Billing;

use Carbon\Carbon;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class PaddleInvoice
{
    private $invoice;

    public function __construct(array $invoice)
    {
        $this->invoice = $invoice;
    }

    public function id(): string
    {
        return $this->invoice['order_id'];
    }

    public function date(): Carbon
    {
        return Carbon::parse($this->invoice['created_at']);
    }

    public function total(): string
    {
        $money = new Money($this->invoice['amount'], new Currency($this->invoice['currency']));

        $numberFormatter = new NumberFormatter(config('paddle.currency_locale'), NumberFormatter::CURRENCY);
        $moneyFormatter  = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());

        return $moneyFormatter->format($money);
    }

    public function status(): string
    {
        return $this->invoice['status'];
    }

    public function receipt(): string
    {
        return $this->invoice['receipt_url'];
    }

    public function asPaddleInvoice(): array
    {
        return $this->invoice;
    }
}
