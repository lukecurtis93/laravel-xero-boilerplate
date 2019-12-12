# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lukecurtis/laravel-xero-boilerplate.svg?style=flat-square)](https://packagist.org/packages/lukecurtis/laravel-xero-boilerplate)
[![Build Status](https://img.shields.io/travis/lukecurtis/laravel-xero-boilerplate/master.svg?style=flat-square)](https://travis-ci.org/lukecurtis/laravel-xero-boilerplate)
[![Quality Score](https://img.shields.io/scrutinizer/g/lukecurtis/laravel-xero-boilerplate.svg?style=flat-square)](https://scrutinizer-ci.com/g/lukecurtis/laravel-xero-boilerplate)
[![Total Downloads](https://img.shields.io/packagist/dt/lukecurtis/laravel-xero-boilerplate.svg?style=flat-square)](https://packagist.org/packages/lukecurtis/laravel-xero-boilerplate)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require lukecurtis/laravel-xero-boilerplate
```

## Usage
Under the hood this package uses xero-php. Add the following to your `.env` file
```
XERO_CONSUMER_KEY=KEY-HERE
XERO_CONSUMER_SECRET=SECRET-HERE
XERO_RSA_PRIVATE_KEY='file:///home/vagrant/code/web-app/storage/privatekey.pem'
XERO_RSA_PUBLIC_KEY='file:///home/vagrant/code/web-app/storage/publickey.cer'
```

Once complete you can publish and configure the config file that has been published and also migrate

```bash
php artisan vendor:publish --provider="Lukecurtis\LaravelXeroBoilerplate\LaravelXeroBoilerplateServiceProvider" 
php artisan migrate
```

Finally, once all authenticated, you'll need to sync your Contacts, Accounts & Items

```bash
php artisan sync:xero --dispatch_now
```

You'll notice the xero tables are now populated.

Next up is to tell the package which model is having invoices - we acheive this by adding the `HasInvoice` trait. So for example if you have jobs in your system you might do the following:
```php
namespace App\Models\Job;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lukecurtis\LaravelXeroBoilerplate\HasInvoice\HasInvoices;
use Lukecurtis\LaravelXeroBoilerplate\HasInvoice\HasInvoicesTrait;

class Job extends Model implements HasInvoices
{
    //...
    use SoftDeletes, 
        HasInvoicesTrait;
    
    //...
}
```

Now you can go ahead and `$job->xeroInvoices` to get all invoices. Lets go ahead and create one:

```php
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroInvoice;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroLineItem;

$invoice = XeroInvoice::create([
    'xero_contact_id'   => $data['xero_contact_id'],
    'type'              => $data['type'],
    'email'             => $data['email'],
    'due_date'          => $data['due_date'],
    'name'              => $data['name'],
    'status'            => $data['status'],
]);
if ($invoice) {
    foreach ($data['xero_line_items'] as $item) {
        $item['xero_invoice_id'] = $invoice->id;
        $xeroLineItem = XeroLineItem::create([
            'xero_invoice_id'   => $data['xero_invoice_id'],
            'xero_item_id'      => $data['xero_item_id'],
            'description'       => $data['description'],
            'quantity'          => $data['quantity'],
            'xero_account_id'   => $data['xero_account_id'],
            'tax_type'          => $data['tax_type'],
            'unit_price'        => $data['unit_price'],
            'total'             => number_format((($data['unit_price'] * $data['quantity']) / 100), 2, '.', '') * 100,
        ]);

    }
}
```
Finally once your invoice is persisted you can link it like so
`$job->addXeroInvoice($invoice);`

This will send the invoice to xero, you can load the line items like so `Invoice::with('xeroLineItems')` for example, like any other Laravel Model.

You don't need to link a xero invoice to a model if need be.
### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email lukesimoncurtis@gmail.com instead of using the issue tracker.

## Credits

- [Luke Curtis](https://github.com/lukecurtis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).