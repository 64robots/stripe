<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use R64\Stripe\Adapters\Charge;
use Stripe\Charge as StripeCharge;

trait ChargeMock
{
    public function createCharge(array $params)
    {
        $stripeCharge = $this->getMockStripeCharge($params);
        $charge = $stripeCharge::create($params, $this->stripeConnectParam());

        m::close();

        if ($charge) {
            return new Charge($charge);
        }
    }

    public function listCharges(array $params)
    {
        $handler = m::mock(self::class);

        $handler
            ->shouldReceive('autoPagingIterator')
            ->andReturn([
                (object) [
                    'status' => 'failed',
                    'id' => 'ch_1',
                ],
                (object) [
                    'status' => 'success',
                    'id' => 'ch_1',
                ],
            ]);

        return $handler;
    }

    protected function getMockStripeCharge($params)
    {
        $charge = m::mock('alias:StripeCharge');

        $charge
            ->shouldReceive('create')
            ->with([
                'customer' => $params['customer'],
                'amount' => $params['amount'],
                'currency' => $params['currency'],
                'source' => $params['source'],
            ], $this->stripeConnectParam())
            ->andReturn($this->getStripeCharge($params));

        $this->successful = true;

        return $charge;
    }

    protected function getStripeCharge($params)
    {
        $charge = new StripeCharge(['id' => 'ch_1']);
        $charge->amount = $params['amount'];
        $charge->currency = $params['currency'];
        $charge->created = time();
        $charge->source = (object) [
            'id' => 'card_1',
            'object' => 'card',
            'name' => $this->faker->name,
            'brand' => $this->faker->creditCardType,
            'last4' => '4242',
            'exp_month' => $this->faker->numberBetween(1, 12),
            'exp_year' => now()->addYear()->year,
            'country' => 'US',
        ];

        return $charge;
    }
}
