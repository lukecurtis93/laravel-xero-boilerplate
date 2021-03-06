<?php

namespace Lukecurtis\LaravelXeroBoilerplate\HasInvoice;

interface HasInvoices
{
    /**
     * Set the polymorphic relation.
     *
     * @return mixed
     */
    public function xeroInvoices();

    /**
     * Add the invoice.
     */
    public function addXeroInvoice($xeroInvoice);

    /**
     * Mark the invoice as paid.
     */
    public function markXeroInvoiceAsPaid($xeroInvoice);
}
