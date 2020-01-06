<?php

namespace App\Integrations\Stripe\Mocks;

use Faker\Factory;

class StripeChargeEvent
{
    public static function create(array $overrides = [])
    {
        return [
            'type' => 'charge.succeeded',
            'data' => [
                'object' => [
                    'id' => data_get($overrides, 'charge.id', 'ch_1DK9QrDqGIY1iWywxqpshuIN'),
                    'amount' => 2500,
                    'created' => 1539284569,
                    'currency' => 'usd',
                    'customer' => data_get($overrides, 'customer.id', 'cus_DlgoBuURhLohaP'),
                    'invoice' => data_get($overrides, 'invoice.id', 'in_1DK9QrDqGIY1iWywHq3brU5l'),
                    'subscription' => data_get($overrides, 'subscription.id', 'sub_9wYF4f51l8CHYz'),
                    'source' => [
                        'id' => data_get($overrides, 'card.id', 'card_1DK9QpDqGIY1iWywvdJjrGZV'),
                        'object' => 'card',
                        'name' => 'Joe Schmoe',
                        'brand' => 'Visa',
                        'last4' => '4242',
                        'exp_month' => '5',
                        'exp_year' => '2020',
                        'country' => 'US',
                        'owner' => [
                            'address' => [
                                'city' => 'DRYDEN',
                                'country' => 'US',
                                'line1' => '4260 HAVENS ROAD',
                                'line2' => '',
                                'postal_code' => '48428',
                                'state' => 'MI',
                            ],
                            'email' => 'someone@mail.com',
                            'name' => 'Shannon Egan',
                            'phone' => '2488249084',
                        ],
                    ],
                ],
            ],
        ];
    }
}
