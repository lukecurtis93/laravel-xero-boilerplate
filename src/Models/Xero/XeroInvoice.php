<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\Traits\Relationship\XeroInvoiceRelationship;

class XeroInvoice extends Model
{
    //
    use SoftDeletes, SoftCascadeTrait, XeroInvoiceRelationship;

    protected $softCascade = ['xeroLineItems'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['paid_at'];

    protected $appends = ['dollar_total', 'dollar_total_incl_gst'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function getDollarTotalAttribute()
    {
        return Money::AUD(round($this->xeroLineItems()->sum('total')));
    }

    public function getDollarTotalInclGstAttribute()
    {
        return Money::AUD(round($this->line_item_total_with_gst));
    }

    public function getLineItemTotalWithGstAttribute()
    {
        return $this->line_item_total * config('laravel-xero-boilerplate.line_items.tax_rate');
    }

    public function getLineItemTotalAttribute()
    {
        return $this->xeroLineItems()->sum('total');
    }

    public function getBalanceRemainingAttribute()
    {
        $total = $this->line_item_total_with_gst;
        $payments = $this->xeroPayments()->sum('amount');

    if ($total) {
            return round($total - $payments);
        }

        return 0;
    }
}
