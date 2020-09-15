<?php

namespace R64\Stripe\Objects;

class Balance
{
    public $object;

    public $available;

    public $connect_reserved;

    public $livemode;

    public $pending;

    public function __construct($balance = null)
    {
        if ($balance && (get_class($balance) === 'Stripe\Balance' || getProperty($balance, 'object') == 'balance')) {
            $this->setFromSTripe($balance);
        }
    }

    public function setFromStripe($balance)
    {
        $this->object = $balance->object;
        $this->available = $balance->available;
        $this->connect_reserved = $balance->connect_reserved;
        $this->livemode = $balance->livemode;
        $this->pending = $balance->pending;
   }
}
