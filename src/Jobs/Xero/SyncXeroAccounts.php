<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroAccount as Account;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Account as XeroAccount;

class SyncXeroAccounts implements ShouldQueue
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
        $accounts = $this->xero->load(XeroAccount::class)->execute();

        foreach ($accounts as $account) {
            $account = Account::updateOrCreate(['xero_id' => $account['AccountID']], [
                'code'                  => $account['Code'],
                'name'                  => $account['Name'],
                'type'                  => $account['Type'],
                'bank_account_number'   => $account['BankAccountNumber'],
                'status'                => $account['Status'],
                'description'           => $account['Description'],
                'bank_account_type'     => $account['BankAccountType'],
                'tax_type'              => $account['TaxType'],
        ]);
        }
    }
}
