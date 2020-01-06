<?php

namespace App\Integrations\Stripe\Objects;

use Carbon\Carbon;

class CardHolder
{
    public $id;

    public $object;

    public $type;

    public $name;

    public $email;

    public $phone_number;

    public $billing;

    public $metadata;

    public $status;

    public $is_default;

    public $livemode;

    public $created;

    public function __construct($cardHolder = null)
    {
        if ($cardHolder && (get_class($cardHolder) === 'Stripe\Issuing\Cardholder' || getProperty($cardHolder, 'object') == 'issuing.cardholder')) {
            $this->setFromSTripe($cardHolder);
        }
    }

    public function setFromStripe($holder)
    {
        $this->id = $holder->id;
        $this->object = $holder->object;
        $this->type = $holder->type;
        $this->status = $holder->status;
        $this->name = $holder->name;
        $this->email = $holder->email;
        $this->phone_number = $holder->phone_number;
        $this->billing = $holder->billing;
        $this->metadata = $holder->metadata;
        $this->is_default = $holder->is_default;
        $this->livemode = $holder->livemode;
        $this->created = Carbon::createFromTimestamp($holder->created);
    }
}
