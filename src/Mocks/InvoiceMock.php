<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Faker\Factory;
use Illuminate\Support\Arr;
use R64\Stripe\Objects\Invoice;
use Stripe\Invoice as StripeInvoice;

trait InvoiceMock
{
    public function createInvoice(array $params)
    {
        $stripeInvoice = $this->getMockStripeInvoice($params);
        $invoice = $stripeInvoice::create($params, $this->stripeConnectParam());

        m::close();

        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    public function getInvoice(string $id)
    {
        $stripeInvoice = $this->getMockStripeInvoice();
        $invoice = $stripeInvoice::retrieve($id, $this->stripeConnectParam());

        m::close();
        
        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    private function getMockStripeInvoice($params = [])
    {
        $invoice = m::mock('alias:StripeInvoice');

        if (Arr::get($params, 'customer')) {
            $invoice
                ->shouldReceive('create')
                ->with([
                    'customer' => $params['customer'],
                    'subscription' => $params['subscription'],
                ], $this->stripeConnectParam())
                ->andReturn($this->getStripeInvoice());
        }

        $invoice
            ->shouldReceive('retrieve')
            ->andReturn($this->getStripeInvoice());

        $this->successful = true;

        return $invoice;
    }

    private function getStripeInvoice()
    {
        $invoice = new StripeInvoice(['id' => 1]);

        $faker = Factory::create();
        $invoice->amount_paid = $faker->numberBetween(100, 1000);
        $invoice->currency = 'usd';
        $invoice->subscription = 1;
        $invoice->receipt_number = $faker->randomNumber();
        $invoice->date = time();

        return $invoice;
    }
}
