<?php

namespace R64\Stripe\Processor;

trait Customer
{
    public function createCustomer(array $data)
    {
        $customer = $this->handler->createCustomer($data);
        $this->recordAttempt();

        return $customer;
    }

    public function updateCustomer(array $data)
    {
        $customer = $this->handler->updateCustomer($data);
        $this->recordAttempt();

        return $customer;
    }

    public function getCustomer($id)
    {
        $customer = $this->handler->getCustomer($id);
        $this->recordAttempt();

        return $customer;
    }

    public function listCustomers(array $data)
    {
        $customers = $this->handler->listCustomers($data);
        $this->recordAttempt();

        return $customers;
    }

    public function deleteCustomer($id)
    {
        $this->handler->deleteCustomer($id);
        $this->recordAttempt();
    }
}
