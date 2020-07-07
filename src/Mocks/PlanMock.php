<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Stripe\Adapters\Plan;
use Stripe\Plan as StripePlan;

trait PlanMock
{
    public function createPlan(array $params)
    {
        $stripePlan = $this->getMockStripePlan('create', $params);
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
            case 'create':
                $plan
                    ->shouldReceive('create')
                    ->with([
                        'amount' => $params['amount'],
                        'currency' => $params['currency'],
                        'interval' => $params['interval'],
                        'product' => $params['product'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripePlan($params));

                break;
        }

        $this->successful = true;

        return $plan;
    }

    protected function getStripePlan($params = [])
    {
        $plan = new StripePlan(['id' => 'price_' . Str::random(12)]);

        $plan->product = Arr::get($params, 'product', 'prod_' . Str::random(12));
        $plan->nickname = Arr::get($params, 'nickname', $this->faker->word);
        $plan->amount = Arr::get($params, 'amount', $this->faker->numberBetween(1000, 5000));
        $plan->interval = Arr::get($params, 'interval', 'month');
        $plan->interval_count = $this->faker->numberBetween(1, 6);
        $plan->billing_scheme = Arr::get($params, 'billing_scheme', 'per_unit');
        $plan->usage_type = $this->faker->word;
        $plan->currency = Arr::get($params, 'currency', 'usd');
        $plan->created = time();

        return $plan;
    }
}
