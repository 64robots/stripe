<?php

namespace R64\Stripe\Processor;

trait Charge
{
    public function createCharge(array $data)
    {
        $charge = $this->handler->createCharge($data);
        $this->recordAttempt();

        return $charge;
    }
}
