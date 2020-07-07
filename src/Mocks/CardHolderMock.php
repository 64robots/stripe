<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Stripe\Adapters\CardHolder;
use Stripe\Issuing\Cardholder as StripeCardHolder;

trait CardHolderMock
{
    public function getCardHolder(string $id)
    {
        $cardHolderClass = $this->getMockStripeCardHolder('retrieve', ['id' => $id]);
        $cardHolder = $cardHolderClass::retrieve($id, $this->stripeConnectParam());

        m::close();

        if ($cardHolder) {
            return new CardHolder($cardHolder);
        }
    }

    public function createCardHolder(array $params)
    {
        $cardHolderClass = $this->getMockStripeCardHolder('create', $params);
        $cardHolder = $cardHolderClass::create($params, $this->stripeConnectParam());

        m::close();

        if ($cardHolder) {
            return new CardHolder($cardHolder);
        }
    }

    public function updateCardHolder(string $id, array $params)
    {
        $cardHolderClass = $this->getMockStripeCardHolder('update', $params);
        $cardHolder = $cardHolderClass::update($id, $this->stripeConnectParam());

        m::close();
        
        if ($cardHolder) {
            return new CardHolder($cardHolder);
        }
    }

    protected function getMockStripeCardHolder(string $slug, $params = [])
    {
        $cardHolder = m::mock('alias:StripeCardHolder');

        switch ($slug) {
            case 'retrieve':
                $cardHolder
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCardHolder($params));

                break;

            case 'create':
                $cardHolder
                    ->shouldReceive('create')
                    ->with([
                        'billing' => $params['billing'],
                        'name' => $params['name'],
                        'type' => $params['type'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCardHolder($params));

                break;

            case 'update':
                $cardHolder
                    ->shouldReceive('update')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCardHolder($params));

                break;
        }
        
        $this->successful = true;

        return $cardHolder;
    }

    protected function getStripeCardHolder(array $params)
    {
        $holder = new StripeCardHolder(['id' => Arr::get($params, 'id', 'ich_'. Str::random(12))]);

        $holder->object = 'issuing.cardholder';
        $holder->email = Arr::get($params, 'email', $this->faker->unique()->safeEmail);
        $holder->type = Arr::get($params, 'type', 'individual');
        $holder->name = Arr::get($params, 'name', $this->faker->name);
        $holder->phone_number = Arr::get($params, 'phone_number');
        $holder->status = Arr::get($params, 'status', 'active');
        $holder->billing = (object) [
            'address' => (object) [
                'line1' => Arr::get($params, 'billing.address.line1', $this->faker->streetAddress),
                'line2' => Arr::get($params, 'billing.address.line2', $this->faker->secondaryAddress),
                'city' => Arr::get($params, 'billing.address.city', $this->faker->city),
                'state' => Arr::get($params, 'billing.address.state', $this->faker->stateAbbr),
                'postal_code' => Arr::get($params, 'billing.address.postal_code', $this->faker->postcode),
                'country' => Arr::get($params, 'billing.address.country', $this->faker->countryCode),
            ],
        ];
        $holder->metadata = (object) Arr::get($params, 'metadata', []);
        $holder->is_default = Arr::get($params, 'is_default', false);
        $holder->livemode = Arr::get($params, 'livemode', false);
        $holder->created = time();

        $this->successful = true;

        return $holder;
    }
}
