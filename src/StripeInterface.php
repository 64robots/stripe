<?php

namespace R64\Stripe;

interface StripeInterface
{
    public function createCharge(array $data);

    public function createCustomer(array $data);

    public function getCustomer(string $id);

    public function updateCustomer(array $data);
}
