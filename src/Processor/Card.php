<?php

namespace R64\Stripe\Processor;

trait Card
{
    public function getCard($customerId, $cardId)
    {
        $card = $this->handler->getCard($customerId, $cardId);
        $this->recordAttempt();

        return $card;
    }

    public function createCard(array $data)
    {
        $card = $this->handler->createCard($data);
        $this->recordAttempt();

        return $card;
    }

    public function updateCard($customerId, $cardId, array $data)
    {
        $card = $this->handler->updateCard($customerId, $cardId, $data);
        $this->recordAttempt();

        return $card;
    }
}
