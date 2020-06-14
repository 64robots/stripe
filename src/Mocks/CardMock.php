<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Faker\Factory;
use Illuminate\Support\Arr;
use R64\Stripe\Objects\Card;
use Stripe\Card as StripeCard;

trait CardMock
{
    public function getCard(string $customerId, string $cardId)
    {
        $stripeCardClass = $this->getMockStripeCard('retrieve', ['id' => $cardId]);
        $card = $stripeCardClass::retrieve($cardId, $this->stripeConnectParam());

        m::close();

        if ($card) {
            return new Card($card);
        }
    }

    public function createCard(array $params)
    {
        $card = $this->getMockStripeCard('create', $params);

        m::close();

        if ($card) {
            return new Card($card);
        }
    }

    public function updateCard(string $customerId, string $cardId, array $params)
    {
        $card = $this->getMockStripeCard('update', $params);

        m::close();

        if ($card) {
            return new Card($card);
        }
    }

    private function getMockStripeCard(string $slug, $params = [])
    {
        $card = m::mock('alias:StripeCard');

        switch ($slug) {
            case 'retrieve':
                $card
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCard($params));

            break;

            case 'create':
                $card
                    ->shouldReceive('create')
                    ->with(['source' => Arr::get($params, 'source')], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCard());

            break;

            case 'update':
                $card
                    ->shouldReceive('update')
                    ->with($params, $this->stripeConnectParam())
                    ->andReturn($this->getStripeCard($params));

            break;
        }
        
        $this->successful = true;

        return $card;
    }

    private function getStripeCard(array $params = [])
    {
        $card = new StripeCard(['id' => 'card_1234']);

        $faker = Factory::create();

        // general info
        $card->brand = $faker->creditCardType;
        $card->object = 'card';
        $card->country = 'US';
        $card->customer = 'cus_1';
        $card->name = null;
        $card->last4 = '4242';
        $card->exp_month = $faker->numberBetween(1, 12);
        $card->exp_year = now()->addYear()->year;

        // address
        $card->address_line1 = Arr::get($params, 'address_line1');
        $card->address_line2 = Arr::get($params, 'address_line2');
        $card->address_city = Arr::get($params, 'address_city');
        $card->address_state = Arr::get($params, 'address_state');
        $card->address_zip = Arr::get($params, 'address_zip');
        $card->address_country = Arr::get($params, 'address_country');
        $card->address_line1_check = Arr::get($params, 'address_line1_check');
        $card->address_zip_check = Arr::get($params, 'address_zip_check');

        return $card;
    }
}
