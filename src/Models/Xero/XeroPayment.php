<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero;

use Illuminate\Database\Eloquent\Model;

class XeroPayment extends Model
{
    //
    protected $fillable = [
        'xero_id',
        'status',
        'amount',
        'payment_type',
        'xero_invoice_id',
        'xero_account_id',
    ];

    protected $appends = ['dollar_amount'];

    public function xeroAccount()
    {
        return $this->belongsTo(XeroAccount::class);
    }

    public function xeroInvoice()
    {
        return $this->belongsTo(XeroInvoice::class);
    }

    public function getDollarAmountAttribute()
    {
        return number_format($this->amount / 100, 2, '.', '');
    }
}
