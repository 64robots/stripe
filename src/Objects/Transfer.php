<?php

namespace R64\Stripe\Objects;

use Carbon\Carbon;

class Transfer
{
    public $id;

    public $object;

    public $amount;

    public $currency;

    public $destination;

    public $created;

    public function __construct($transfer = null)
    {
        if ($transfer && get_class($transfer) === 'Stripe\Transfer') {
            $this->setFromStripe($transfer);
        }
    }

    public function setFromStripe(\Stripe\Transfer $transfer)
    {
        $this->id = $transfer->id;
        $this->object = 'transfer';
        $this->amount = $transfer->amount;
        $this->currency = $transfer->currency;
        $this->destination = $transfer->destination;
        $this->source_transaction = $transfer->source_transaction;
        $this->created = Carbon::createFromTimestamp($transfer->created);
    }
}
