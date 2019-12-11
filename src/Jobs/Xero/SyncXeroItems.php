<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero;

use Illuminate\Bus\Queueable;
use Lukecurtis\LaravelXeroBoilerplate\Models\XeroItem as Item;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Lukecurtis\LaravelXeroBoilerplate\Models\XeroAccount as Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Item as XeroItem;

class SyncXeroItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $xero;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->xero = new PrivateApplication(config('laravel-xero-boilerplate'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $items = $this->xero->load(XeroItem::class)->execute();

        foreach ($items as $item) {
            $item = Item::updateOrCreate(['xero_id' => $item['ItemID']], [
                'code'              => $item['Code'],
                'name'              => $item['Name'],
                'description'       => $item['Description'],
                'unit_price'        => $item['SalesDetails']['UnitPrice'],
                'tax_type'          => $item['SalesDetails']['TaxType'],
                'xero_account_id'   => $item['SalesDetails']['AccountCode'] && Account::whereCode($item['SalesDetails']['AccountCode'])->first() ? Account::whereCode($item['SalesDetails']['AccountCode'])->first()->id : null,
        ]);
        }
    }
}
