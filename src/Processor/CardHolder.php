<?php

namespace R64\Stripe\Processor;

trait CardHolder
{
    public function getCardHolder($id)
    {
        $cardHolder = $this->handler->getCardHolder($id);
        $this->recordAttempt();

        return $cardHolder;
    }

    public function createCardHolder(array $data)
    {
        $cardHolder = $this->handler->createCardHolder($data);
        $this->recordAttempt();

        return $cardHolder;
    }

    public function updateCardHolder($id, array $data)
    {
        $cardHolder = $this->handler->updateCardHolder($id, $data);
        $this->recordAttempt();

        return $cardHolder;
    }
}
