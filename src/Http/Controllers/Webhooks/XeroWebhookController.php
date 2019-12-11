<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Http\Controllers\Webhooks;

use XeroPHP\Webhook;
use Illuminate\Http\Request;
use XeroPHP\Application\PrivateApplication;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroInvoice;
use Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero\SyncXeroInvoice;
use Lukecurtis\LaravelXeroBoilerplate\Http\Controllers\Controller;

class XeroWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $webhook = new Webhook(new PrivateApplication(config('laravel-xero-boilerplate')), $request->getContent());

        foreach ($webhook->getEvents() as $event) {
            $invoice = XeroInvoice::whereXeroGuid($event->getResourceId())->first();
            if ($invoice) {
                dispatch_now(new SyncXeroInvoice($invoice));
            }
        }

        $response = response()->json(null, 200);
        $response->setContent(null);

        return $response;
    }
}
