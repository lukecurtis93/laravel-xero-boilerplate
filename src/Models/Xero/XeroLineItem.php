<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\Traits\Relationship\XeroLineItemRelationship;

class XeroLineItem extends Model
{
    //
    use SoftDeletes, SoftCascadeTrait, XeroLineItemRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'xero_item_id',
        'description',
        'quantity',
        'unit_price',
        'xero_account_id',
        'xero_item_id',
        'xero_id',
        'tax_type',
        'xero_invoice_id',
        'total',
    ];

    protected $appends = ['dollar_total', 'dollar_unit_price'];

    public function getDollarTotalAttribute()
    {
        return number_format($this->total / 100, 2, '.', '');
    }

    public function getDollarUnitPriceAttribute()
    {
        return number_format($this->unit_price / 100, 2, '.', '');
    }
}
