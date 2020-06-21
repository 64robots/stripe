<?php

namespace R64\Stripe\Adapters;

use Carbon\Carbon;

class Charge
{
    public $id;

    public $amount;

    public $currency;

    public $created;

    public $card_id;

    public function __construct($charge = null)
    {
        if ($charge && get_class($charge) === 'Stripe\Charge') {
            $this->setFromStripe($charge);
        }
    }

    public function setFromStripe(\Stripe\Charge $charge)
    {
        $this->id = $charge->id;
        $this->card_id = $charge->source->id;
        $this->amount = $charge->amount;
        $this->currency = $charge->currency;
        $this->created = Carbon::createFromTimestamp($charge->created);
        $this->card = new Card($charge->source);
    }
}
