<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Console\Commands;

use Illuminate\Console\Command;
use Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero\SyncXeroAccounts;
use Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero\SyncXeroContacts;
use Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero\SyncXeroItems;

class SyncXero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:xero {--dispatch_now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync with xero accounts, items and contacts, add --dispatch_now flag to force dispatch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        if ($this->option('dispatch_now')) {
            dispatch_now(new SyncXeroAccounts);
            dispatch_now(new SyncXeroContacts);
            dispatch_now(new SyncXeroItems);
        } else {
            SyncXeroAccounts::withChain([
                new SyncXeroContacts,
                new SyncXeroItems,
            ])->dispatch();
        }
    }
}
