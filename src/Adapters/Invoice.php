<?php

namespace R64\Stripe\Adapters;

use R64\Stripe\Traits\ParsesTimestamp;
use Stripe\Invoice as StripeInvoice;

class Invoice
{
    use ParsesTimestamp;

    public $id;

    public $amount_paid;

    public $currency;

    public $subscription;

    public $receipt_number;

    public $date;

    public function __construct($invoice = null)
    {
        if ($invoice && get_class($invoice) === 'Stripe\Invoice') {
            $this->setFromStripe($invoice);
        }
    }

    public function setFromStripe(StripeInvoice $invoice)
    {
        $this->id = $invoice->id;
        $this->amount_paid = $invoice->amount_paid;
        $this->currency = $invoice->currency;
        $this->subscription = $invoice->subscription;
        $this->receipt_number = $invoice->receipt_number;
        $this->date = $this->carbonFromTimestamp($invoice->date);
    }
}
