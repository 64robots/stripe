<?php

namespace R64\Stripe\Mocks;

use Stripe\Stripe;
use Faker\Factory;
use Illuminate\Support\Arr;
use R64\Stripe\StripeInterface;
use R64\Stripe\ResponseStatusTrait;

class MockHandler implements StripeInterface
{
    use CardMock;
    use PlanMock;
    use TokenMock;
    use ChargeMock;
    use InvoiceMock;
    use ProductMock;
    use CustomerMock;
    use CardHolderMock;
    use InvoiceItemMock;
    use SubscriptionMock;
    use ResponseStatusTrait;


    /**
     * Stripe connect id.
     *
     * @var string
     */
    private $stripeConnectId;

    /**
     * Determines whether to skip stripe connect
     * 
     * @var string
     */
    private $skipConnect;

    /**
     * The currency of the transaction
     * 
     * @var string
     */
    private $currency;

    /**
     * Faker generator instance
     * 
     * @var Faker\Generator
     */
    private $faker;

    public function __construct(array $options = [])
    {
        $secret = Arr::has($options, 'secret_key') ? $options['secret_key'] : config('stripe.secret');
        Stripe::setApiKey($secret);

        $this->stripeConnectId = Arr::get($options, 'stripe_connect_id');
        $this->skipConnect = Arr::get($options, 'skip_stripe_connect', true);

        $this->faker = Factory::create();
    }    

    protected function stripeConnectParam()
    {
        if ($this->skipConnect) {
            return;
        }

        return ['stripe_account' => $this->stripeConnectId];
    }
}
