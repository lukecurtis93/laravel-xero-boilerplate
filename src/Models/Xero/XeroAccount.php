<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero;

use Illuminate\Database\Eloquent\Model;

class XeroAccount extends Model
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
        'type',
        'bank_account_number',
        'status',
        'description',
        'bank_account_type',
        'tax_type',
    ];
}
