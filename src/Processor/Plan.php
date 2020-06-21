<?php

namespace R64\Stripe\Processor;

trait Plan
{
    public function createPlan(array $data)
    {
        $plan = $this->handler->createPlan($data);
        $this->recordAttempt();

        return $plan;
    }
}
