<?php

namespace R64\Stripe\Processor;

trait Product
{
    public function createProduct(array $data)
    {
        $product = $this->handler->createProduct($data);
        $this->recordAttempt();

        return $product;
    }
}
