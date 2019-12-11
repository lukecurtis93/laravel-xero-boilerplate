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

class DeleteXeroInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;

    protected $xero;

    protected $invoiceRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaravelInvoice $invoice)
    {
        //
        $this->xero = new PrivateApplication(config('laravel-xero-boilerplate'));

        $this->invoiceRepository = new InvoiceRepository(new LineItemRepository);

        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xeroInvoice = $this->xero->loadByGUID(XeroInvoice::class, $this->invoice->xero_guid)->setStatus('DELETED')->save();
    }
}
