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

namespace KodeKeep\Paddle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use KodeKeep\Paddle\Webhooks\Actions\VerifyWebhook;
use KodeKeep\Paddle\Webhooks\Events\Event as WebhookEvent;

class WebhookController
{
    public function handleWebhook(Request $request, VerifyWebhook $action)
    {
        $payload = collect($request->json()->all());

        $action->execute(
            config('paddle.vendor_public_key'),
            $request->json('p_signature'),
            $payload->reject(function ($value, $key) { return $key === 'p_signature'; })->all()
        );

        try {
            Event::dispatch(WebhookEvent::new($payload->all()));
        } catch (\Throwable $th) {
            return $this->missingMethod();
        }

        return $this->successMethod();
    }

    private function successMethod(): Response
    {
        return new Response('Webhook Handled', 200);
    }

    private function missingMethod(): Response
    {
        return new Response();
    }
}
