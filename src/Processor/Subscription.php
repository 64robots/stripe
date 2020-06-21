<?php

namespace R64\Stripe\Processor;

trait Subscription
{
    public function createSubscription(array $data)
    {
        $subscription = $this->handler->createSubscription($data);
        $this->recordAttempt();

        return $subscription;
    }

    public function getSubscription($id)
    {
        $subscription = $this->handler->getSubscription($id);
        $this->recordAttempt();

        return $subscription;
    }
}
