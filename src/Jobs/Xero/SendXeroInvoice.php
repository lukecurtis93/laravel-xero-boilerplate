<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroInvoice as LaravelInvoice;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Contact as XeroContact;
use XeroPHP\Models\Accounting\Invoice as XeroInvoice;
use XeroPHP\Models\Accounting\Invoice\LineItem as XeroLineItem;

class SendXeroInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;

    protected $xero;

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
        //
        $xeroInvoice = new XeroInvoice($this->xero);

        $xeroInvoice->setContact($this->xero->loadByGUID(XeroContact::class, $this->invoice->xeroContact->xero_id))
                    ->setReference($this->invoice->name)
                    ->setType($this->invoice->type)
                    ->setCurrencyCode('AUD')
                    ->setDueDate(new DateTime($this->invoice->due_date))
                    ->setStatus($this->invoice->status);

        if ($this->invoice->status == 'AUTHORISED') {
            $xeroInvoice->setSentToContact(true);
        }

        foreach ($this->invoice->xeroLineItems as $item) {
            $xeroLineItem = new XeroLineItem($this->xero);
            $xeroLineItem->setDescription($item->description)
                         ->setQuantity($item->quantity)
                         ->setUnitAmount($item->dollar_unit_price)
                         ->setItemCode($item->code)
                         ->setAccountCode($item->xeroAccount->code);

            $xeroInvoice->addLineItem($xeroLineItem);
        }

        if ($xeroInvoice->save()) {
            $this->invoice->update([
                'xero_id' => $xeroInvoice->getInvoiceNumber(),
                'xero_guid' => $xeroInvoice->getGUID(),
            ]);

            $xeroInvoice = $this->xero->loadByGUID(XeroInvoice::class, $this->invoice->xero_id);
        }
    }
}
