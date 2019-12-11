<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero;

use Lukecurtis\LaravelXeroBoilerplate\Models\XeroInvoice as LaravelInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Invoice as XeroInvoice;

class SyncXeroInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $xero;
    protected $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaravelInvoice $invoice)
    {
        //
        $this->xero = new PrivateApplication(config('laravel-xero-boilerplate'));
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xeroInvoice = $this->xero->loadByGuid(XeroInvoice::class, $this->invoice->xero_id);

        $this->invoice->update([
            'status' => $xeroInvoice->getStatus(),
            'payments' => $xeroInvoice->getPayments(),
        ]);

        return $this->invoice;
    }
}
