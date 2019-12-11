<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Jobs\Xero;

use Lukecurtis\LaravelXeroBoilerplate\Models\XeroInvoice as LaravelInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Xero\XeroAccount as Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Account as XeroAccount;
use XeroPHP\Models\Accounting\Invoice as XeroInvoice;
use XeroPHP\Models\Accounting\Payment as XeroPayment;

class MarkXeroInvoiceAsPaid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;

    protected $xero;

    protected $invoiceRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaravelInvoice $invoice)
    {
        //
        $this->xero = new PrivateApplication(config('laravel-xero-boilerplate'));

        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xeroInvoice = $this->xero->loadByGUID(XeroInvoice::class, $this->invoice->xero_guid);

        $xeroInvoice->setStatus('AUTHORISED');

        if ($xeroInvoice->save()) {
            $payment = new XeroPayment($this->xero);

            $account = $this->xero->load(XeroAccount::class)
                ->where('code', Account::whereName(env('XERO_SALES_ACCOUNT'))->first()->code)
                ->execute()
                ->first();

            $payment->setInvoice($xeroInvoice)
                    ->setAccount($account)
                    ->setDate(\Carbon\Carbon::now())
                    ->setAmount($this->invoice->balance_remaining / 100)
                    ->save();

            $this->invoice->update([
                'status' => 'PAID',
                'paid_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
