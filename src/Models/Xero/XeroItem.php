<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero;

use Illuminate\Database\Eloquent\Model;

class XeroItem extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'xero_id',
        'code',
        'name',
        'description',
        'unit_price',
        'tax_type',
        'xero_account_id',
    ];
}
