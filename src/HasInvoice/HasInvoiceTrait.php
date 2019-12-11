<?php
namespace Lukecurtis\LaravelXeroBoilerplate\HasInvoice;

use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroInvoice;
use Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero\SendXeroInvoice;
use Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero\MarkXeroInvoiceAsPaid;

trait HasInvoicesTrait
{
    /**
     * Set the polymorphic relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function xeroInvoices()
    {
        return $this->morphMany(config('laravel-xero-boilerplate.invoice_model'), 'model');
    }

    public function addXeroInvoice(XeroInvoice $invoice)
    {
        dispatch_now(new SendXeroInvoice($invoice));
        $this->xeroInvoices()->save($invoice);

        return $invoice;
    }

    public function markXeroInvoiceAsPaid(XeroInvoice $invoice)
    {
        dispatch_now(new MarkXeroInvoiceAsPaid($invoice));
        
        return $invoice;
    }
}