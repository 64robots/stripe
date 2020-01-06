<?php

namespace App\Integrations\Stripe\Objects;

use App\Integrations\Stripe\Traits\ParsesTimestamp;
use Stripe\InvoiceItem as StripeInvoiceItem;

class InvoiceItem
{
    use ParsesTimestamp;

    public $id;

    public $object;

    public $amount;

    public $currency;

    public $customer;

    public $subscription;

    public $invoice;

    public $date;

    public function __construct($invoiceItem = null)
    {
        if ($invoiceItem && get_class($invoiceItem) === 'Stripe\InvoiceItem') {
            $this->setFromStripe($invoiceItem);
        }
    }

    public function setFromStripe(StripeInvoiceItem $invoiceItem)
    {
        $this->id = $invoiceItem->id;
        $this->object = $invoiceItem->object;
        $this->amount = $invoiceItem->amount;
        $this->currency = $invoiceItem->currency;
        $this->customer = $invoiceItem->customer;
        $this->subscription = $invoiceItem->subscription;
        $this->invoice = $invoiceItem->invoice;
        $this->date = $this->carbonFromTimestamp($invoiceItem->date);
    }
}
