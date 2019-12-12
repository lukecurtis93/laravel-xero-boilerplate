<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero\Traits\Relationship;

use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroContact;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroLineItem;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroPayment;

/**
 * Class XeroInvoiceRelationship.
 */
trait XeroInvoiceRelationship
{
    public function xeroLineItems()
    {
        return $this->hasMany(XeroLineItem::class);
    }

    public function xeroContact()
    {
        return $this->belongsTo(XeroContact::class);
    }

    public function xeroPayments()
    {
        return $this->hasMany(XeroPayment::class);
    }
}
