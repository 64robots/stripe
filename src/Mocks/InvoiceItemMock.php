<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use R64\Stripe\Adapters\InvoiceItem;
use Stripe\InvoiceItem as StripeInvoiceItem;

trait InvoiceItemMock
{
    public function createInvoiceItem(array $params)
    {
        $stripeInvoiceItem = $this->getMockStripeInvoiceItem('create', $params);
        $invoiceItem = $stripeInvoiceItem::create($params, $this->stripeConnectParam());

        m::close();
        
        if ($invoiceItem) {
            return new InvoiceItem($invoiceItem);
        }
    }

    private function getMockStripeInvoiceItem(string $slug, $params)
    {
        $invoiceItem = m::mock('alias:StripeInvoiceItem');

        switch ($slug) {
            case 'create':
                $invoiceItem
                    ->shouldReceive('create')
                    ->with([
                        'customer' => $params['customer'],
                        'amount' => $params['amount'],
                        'subscription' => $params['subscription'],
                        'currency' => $params['currency'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeInvoiceItem($params));
                break;
        }

        $this->successful = true;

        return $invoiceItem;
    }

    private function getStripeInvoiceItem($params)
    {
        $invoiceItem = new StripeInvoiceItem(['id' => 'ii_' . Str::random(12)]);

        $invoiceItem->customer = Arr::get($params, 'customer', 'cus_' . Str::random(12));
        $invoiceItem->subscription = Arr::get($params, 'subscription', 'sub_' . Str::random(12));;
        $invoiceItem->amount = Arr::get($params, 'amount', '1000');
        $invoiceItem->currency = Arr::get($params, 'currency', 'usd');;
        $invoiceItem->object = 'invoiceitem';
        $invoiceItem->invoice = null;
        $invoiceItem->date = time();

        return $invoiceItem;
    }
}
