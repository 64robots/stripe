<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Stripe\Adapters\Subscription;
use Stripe\Subscription as StripeSubscription;

trait SubscriptionMock
{
    public function createSubscription(array $params)
    {
        $stripeSubscription = $this->getMockStripeSubscription('create-subscription', $params);
        $subscription = $stripeSubscription::create($params, $this->stripeConnectParam());

        m::close();

        if ($subscription) {
            return new Subscription($subscription);
        }
    }

    public function getSubscription(string $id)
    {
        $stripeSubscription = $this->getMockStripeSubscription('retrieve-subscription');
        $subscription = $stripeSubscription::retrieve($id, $this->stripeConnectParam());

        m::close();
        
        if ($subscription) {
            return new Subscription($subscription);
        }
    }

    protected function getMockStripeSubscription(string $slug, $params = [])
    {
        $subscription = m::mock('alias:StripeSubscription');

        if (Arr::get($params, 'customer')) {
            $subscription
                ->shouldReceive('create')
                ->with([
                    'customer' => $params['customer'],
                    'items' => $params['items'],
                ], $this->stripeConnectParam())
                ->andReturn($this->getStripeSubscription($params));
        }

        $subscription
            ->shouldReceive('retrieve')
            ->andReturn($this->getStripeSubscription());

        $this->successful = true;

        return $subscription;
    }

    protected function getStripeSubscription($params = [])
    {
        $subscription = new StripeSubscription(['id' => 'sub_1']);

        $faker = Factory::create();
        $subscription->plan = (object) [
            'id' => count($params) ? $params['items']['0']['plan'] : 'plan_'.Str::random(10),
            'amount' => 2500,
        ];
        $subscription->customer = count($params) ? $params['customer'] : 'cus_'.Str::random(10);
        $subscription->quantity = 1;
        $subscription->billing = $faker->numberBetween(10, 100);
        $subscription->discount = $faker->numberBetween(10, 100);
        $subscription->cancel_at_period_end = false;
        $subscription->billing_cycle_anchor = now()->addDays(10)->timestamp;
        $subscription->ended_at = now()->addDays(30)->timestamp;
        $subscription->canceled_at = now()->addDays(30)->timestamp;
        $subscription->current_period_end = now()->addDays(30)->timestamp;
        $subscription->current_period_start = time();
        $subscription->days_until_due = $faker->numberBetween(1, 30);
        $subscription->created = time();

        return $subscription;
    }
}
