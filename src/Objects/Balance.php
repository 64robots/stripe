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

   public function getAvailable($currency)
   {
       $currency = strtolower($currency);

       $availableBalance = collect($this->available)
           ->whereIn('currency', [$currency, strtoupper($currency)])
           ->first();

       return $availableBalance->amount ?? null;
   }

   public function getPending($currency)
   {
       $currency = strtolower($currency);

       $pendingBalance = collect($this->pending)
           ->whereIn('currency', [$currency, strtoupper($currency)])
           ->first();

       return $pendingBalance->amount ?? null;
   }
}
