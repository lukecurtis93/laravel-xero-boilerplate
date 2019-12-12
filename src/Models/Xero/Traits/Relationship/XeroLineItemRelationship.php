<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero\Traits\Relationship;

use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroAccount;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroInvoice;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroItem;

/**
 * Class XeroLineItemRelationship.
 */
trait XeroLineItemRelationship
{
    public function invoice()
    {
        return $this->belongsTo(XeroInvoice::class);
    }

    public function xeroItem()
    {
        return $this->belongsTo(XeroItem::class);
    }

    public function xeroAccount()
    {
        return $this->belongsTo(XeroAccount::class);
    }
}
