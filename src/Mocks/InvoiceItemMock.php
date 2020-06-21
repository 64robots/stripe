<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use R64\Stripe\Adapters\InvoiceItem;
use Stripe\InvoiceItem as StripeInvoiceItem;

trait InvoiceItemMock
{
    public function createInvoiceItem(array $params)
    {
        $stripeInvoiceItem = $this->getMockStripeInvoiceItem($params);
        $invoiceItem = $stripeInvoiceItem::create($params, $this->stripeConnectParam());

        m::close();
        
        if ($invoiceItem) {
            return new InvoiceItem($invoiceItem);
        }
    }

    private function getMockStripeInvoiceItem($params)
    {
        $invoiceItem = m::mock('alias:StripeInvoiceItem');

        $invoiceItem
            ->shouldReceive('create')
            ->with([
                'customer' => $params['customer'],
                'amount' => $params['amount'],
                'subscription' => $params['subscription'],
                'currency' => $params['currency'],
            ], $this->stripeConnectParam())
            ->andReturn($this->getStripeInvoiceItem($params));

        $this->successful = true;

        return $invoiceItem;
    }

    private function getStripeInvoiceItem($params)
    {
        $invoiceItem = new StripeInvoiceItem(['id' => 1]);

        $invoiceItem->customer = $params['customer'];
        $invoiceItem->subscription = $params['subscription'];
        $invoiceItem->amount = $params['amount'];
        $invoiceItem->currency = $params['currency'];
        $invoiceItem->object = 'invoiceitem';
        $invoiceItem->invoice = null;
        $invoiceItem->date = time();

        return $invoiceItem;
    }
}
