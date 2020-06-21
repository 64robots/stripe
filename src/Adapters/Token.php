<?php

namespace R64\Stripe\Adapters;

class Token
{
    public $id;

    public $object;

    public $card;

    public $type;

    public $created;

    public function __construct($token = null)
    {
        if ($token && get_class($token) === 'Stripe\Token') {
            $this->setFromStripe($oken);
        }
    }

    public function setFromStripe($token)
    {
        $this->id = $token->id;
        $this->object = $token->object;
        $this->card = new Card($token->card);
        $this->type = $token->type;
        $this->created = $this->carbonFromTimestamp($token->created);
    }
}
