<?php

namespace R64\Stripe\Processor;

trait InvoiceItem
{
    public function createInvoiceItem(array $data)
    {
        $invoiceItem = $this->handler->createInvoiceItem($data);
        $this->recordAttempt();

        return $invoiceItem;
    }
}
