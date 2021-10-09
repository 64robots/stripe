<?php

namespace R64\Stripe\Objects;

use Carbon\Carbon;

class Payout
{
    public $id;
    public $object;
    public $pending;
    public $amount;
    public $arrival_date;
    public $automatic;
    public $balance_transaction;
    public $created;
    public $currency;
    public $description;
    public $destination;
    public $failure_balance_transaction;
    public $failure_code;
    public $failure_message;
    public $livemode;
    public $metadata;
    public $method;
    public $source_type;
    public $statement_descriptor;
    public $status;
    public $type;

    public function __construct($payout = null)
    {
        if ($payout && (get_class($payout) === 'Stripe\Payout' || getProperty($payout, 'object') == 'payout')) {
            $this->setFromSTripe($payout);
        }
    }

    public function setFromStripe($payout)
    {
        $this->id = $payout->id ?? null;
        $this->object = $payout->object ?? null;
        $this->amount = $payout->amount ?? null;
        $this->arrival_date = $payout->arrival_date ? Carbon::createFromTimestamp($payout->arrival_date) : null;
        $this->automatic = $payout->automatic ?? null;
        $this->balance_transaction = $payout->balance_transaction ?? null;
        $this->created = $payout->created ? Carbon::createFromTimestamp($payout->created) : null;
        $this->currency = $payout->currency ?? null;
        $this->description = $payout->description ?? null;
        $this->destination = $payout->destination ?? null;
        $this->failure_balance_transaction = $payout->failure_balance_transaction ?? null;
        $this->failure_code = $payout->failure_code ?? null;
        $this->failure_message = $payout->failure_message ?? null;
        $this->livemode = $payout->livemode ?? null;
        $this->metadata = $payout->metadata ?? null;
        $this->method = $payout->method ?? null;
        $this->source_type = $payout->source_type ?? null;
        $this->statement_descriptor = $payout->statement_descriptor ?? null;
        $this->status = $payout->status ?? null;
        $this->type = $payout->type ?? null;
    }
}
