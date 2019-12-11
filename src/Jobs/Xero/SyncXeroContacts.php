<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero;

use Illuminate\Bus\Queueable;
use  Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroAccount;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Lukecurtis\LaravelXeroBoilerplate\Models\Xero\XeroContact as Contact;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Contact as XeroContact;

class SyncXeroContacts implements ShouldQueue
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
        $persistedContacts = collect();

        $contacts = $this->xero->load(XeroContact::class)->where('IsCustomer', true)->execute();

        foreach ($contacts as $contact) {
            $xeroContact = Contact::updateOrCreate(['xero_id' => $contact['ContactID']], [
                'contact_number'                => $contact['ContactNumber'],
                'account_number'                => $contact['AccountNumber'],
                'contact_status'                => $contact['ContactStatus'],
                'first_name'                    => $contact->getName() ? $contact->getName() : $contact['FirstName'],
                'last_name'                     => $contact['LastName'],
                'email_address'                 => $contact['EmailAddress'],
                'is_supplier'                   => $contact['IsSupplier'],
                'is_customer'                   => $contact['IsCustomer'],
                'tax_number'                    => $contact['TaxNumber'],
                'sales_xero_account_id'         => isset($contact['SalesDefaultAccountCode']) && XeroAccount::whereXeroId($contact['SalesDefaultAccountCode'])->first() ? XeroAccount::whereXeroId($contact['SalesDefaultAccountCode'])->first()->id : null,
                'purchases_xero_account_id'     => isset($contact['PurchasesDefaultAccountCode']) && XeroAccount::whereXeroId($contact['PurchasesDefaultAccountCode'])->first() ? XeroAccount::whereXeroId($contact['PurchasesDefaultAccountCode'])->first()->id : null,
            ]);
            $xeroContact->extra_attributes->set('xero.addresses', $contact['Addresses']);
            $xeroContact->save();
            $persistedContacts->push($xeroContact);
        }

        //Delete the contacts that are no longer customers
        Contact::whereNotIn('id', $persistedContacts->pluck('id'))->delete();
    }
}
