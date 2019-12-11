<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Models\Xero;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasSchemalessAttributes;

class XeroContact extends Model
{
    use HasSchemalessAttributes, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'xero_id',
        'contact_number',
        'account_number',
        'contact_status',
        'first_name',
        'last_name',
        'email_address',
        'tax_number',
        'extra_attributes',
        'is_supplier',
        'is_customer',
        'deleted_at',
        'sales_xero_account_id',
        'purchases_xero_account_id',
    ];

    public $casts = [
        'extra_attributes' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function xeroInvoices()
    {
        return $this->hasMany(XeroInvoice::class);
    }
}
