<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Stripe\Adapters\Invoice;
use Stripe\Invoice as StripeInvoice;

trait InvoiceMock
{
    public function createInvoice(array $params)
    {
        $stripeInvoice = $this->getMockStripeInvoice('create', $params);
        $invoice = $stripeInvoice::create($params, $this->stripeConnectParam());

        m::close();

        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    public function getInvoice(string $id)
    {
        $stripeInvoice = $this->getMockStripeInvoice('retrieve', ['id' => $id]);
        $invoice = $stripeInvoice::retrieve($id, $this->stripeConnectParam());

        m::close();
        
        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    private function getMockStripeInvoice(string $slug, $params = [])
    {
        $invoice = m::mock('alias:StripeInvoice');

        switch ($slug) {
            case 'create':
                $invoice
                    ->shouldReceive('create')
                    ->with([
                        'customer' => $params['customer'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeInvoice());
                break;

            case 'retrieve':
                $invoice
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeInvoice());
                break;
        }

        $this->successful = true;

        return $invoice;
    }

    private function getStripeInvoice()
    {
        $invoice = new StripeInvoice(['id' => 'in_' . Str::random(12)]);

        $invoice->amount_paid = $this->faker->numberBetween(100, 1000);
        $invoice->currency = 'usd';
        $invoice->subscription = 'sub_' . Str::random(12);
        $invoice->receipt_number = $this->faker->randomNumber();
        $invoice->date = time();

        return $invoice;
    }
}
