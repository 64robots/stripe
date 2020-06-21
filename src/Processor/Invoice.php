<?php

namespace R64\Stripe\Processor;

trait Invoice
{
    public function createInvoice(array $data)
    {
        $invoice = $this->handler->createInvoice($data);
        $this->recordAttempt();

        return $invoice;
    }

    public function getInvoice($id)
    {
        $invoice = $this->handler->getInvoice($id);
        $this->recordAttempt();

        return $invoice;
    }
}
