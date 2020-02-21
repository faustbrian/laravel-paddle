<?php

use Illuminate\Support\Facades\Route;
use KodeKeep\Paddle\Http\Controllers\WebhookController;

Route::post('paddle/webhook', [WebhookController::class, 'handleWebhook'])->name('paddle.webhook');
