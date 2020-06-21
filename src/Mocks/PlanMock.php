<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Faker\Factory;
use R64\Stripe\Adapters\Plan;
use Stripe\Plan as StripePlan;

trait PlanMock
{
    public function createPlan(array $params)
    {
        $stripePlan = $this->getMockStripePlan('create-plan', $params);
        $plan = $stripePlan::create($params, $this->stripeConnectParam());

        m::close();
        
        if ($plan) {
            return new Plan($plan);
        }
    }

    protected function getMockStripePlan(string $slug, $params = [])
    {
        $plan = m::mock('alias:StripePlan');

        switch ($slug) {
            case 'create-plan':
                $plan
                    ->shouldReceive('create')
                    ->with([
                        'product' => $params['product'],
                        'nickname' => $params['nickname'],
                        'interval' => $params['interval'],
                        'billing_scheme' => $params['billing_scheme'],
                        'amount' => $params['amount'],
                        'currency' => $params['currency'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripePlan($params));

                break;
        }

        $this->successful = true;

        return $plan;
    }

    protected function getStripePlan($params = [])
    {
        $plan = new StripePlan(['id' => 1]);

        $faker = Factory::create();
        $plan->product = count($params) ? $params['product'] : 1;
        $plan->nickname = count($params) ? $params['nickname'] : $faker->word;
        $plan->amount = count($params) ? $params['amount'] : $faker->numberBetween(1000, 5000);
        $plan->interval = count($params) ? $params['interval'] : 'month';
        $plan->interval_count = $faker->numberBetween(1, 6);
        $plan->billing_scheme = count($params) ? $params['billing_scheme'] : 'per_unit';
        $plan->usage_type = $faker->word;
        $plan->currency = count($params) ? $params['currency'] : 'usd';
        $plan->created = time();

        return $plan;
    }
}
