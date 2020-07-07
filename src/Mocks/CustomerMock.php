<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Stripe\Adapters\Customer;
use Stripe\Customer as StripeCustomer;

trait CustomerMock
{
    public function createCustomer(array $params)
    {
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $customer = $stripeCustomer::create($params, $this->stripeConnectParam());

        m::close();

        if ($customer) {
            return new Customer($customer);
        }
    }

    public function listCustomers(array $params)
    {
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $result = $stripeCustomer::all($params, $this->stripeConnectParam());

        m::close();

        if ($result) {
            return collect($result->data);
        }

        return collect([]);
    }

    public function getCustomer(string $id)
    {
        $stripeCustomer = $this->getMockStripeCustomer(['id' => $id]);
        $customer = $stripeCustomer::retrieve($id, $this->stripeConnectParam());

        m::close();

        if ($customer) {
            return new Customer($customer);
        }
    }

    public function updateCustomer(array $params)
    {
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $customers = $this->listCustomers(['email' => $params['email']]);

        m::close();

        if (! $customers->count() == 1) {
            abort(400, 'Cannot update customer: '.$customers->count());
        }
        $customer = $customers->first();
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $updatedCustomer = $stripeCustomer::update(
            $params['id'],
            ['description' => $params['description']],
            $this->stripeConnectParam()
        );

        if ($updatedCustomer) {
            return new Customer($updatedCustomer);
        }
    }

    protected function getMockStripeCustomer($params = [])
    {
        $customer = m::mock('alias:StripeCustomer');

        $customer
            ->shouldReceive('create')
            ->with($params, $this->stripeConnectParam())
            ->andReturn($this->getStripeCustomer($params));

        $customer
            ->shouldReceive('retrieve')
            ->with(Arr::get($params, 'id'), $this->stripeConnectParam())
            ->andReturn($this->getStripeCustomer());

        $customer
            ->shouldReceive('all')
            ->with([
                'email' => Arr::get($params, 'email'),
            ], $this->stripeConnectParam())
            ->andReturn((object) ['data' => $this->getStripeCustomer()]);

        $customer
            ->shouldReceive('update')
            ->with(
                1,
                ['description' => Arr::get($params, 'description')],
                $this->stripeConnectParam()
            )
            ->andReturn($this->getStripeCustomer(['description' => Arr::get($params, 'description'), 'email' => Arr::get($params, 'email')]));

        $this->successful = true;

        return $customer;
    }

    private function setParams(array $params)
    {
        $params['id'] = $params['id'] ?? 'cus_uRBZyegl'; // 'cus_' . Str::random(8);
        $params['description'] = Arr::get($params, 'description', $this->faker->paragraph);
        $params['source'] = 'tok_visa';
        $params['email'] = Arr::get($params, 'email', $this->faker->safeEmail);
        $params['metadata']['first_name'] = Arr::get($params, 'metadata.first_name', $this->faker->firstName);
        $params['metadata']['last_name'] = Arr::get($params, 'metadata.last_name', $this->faker->lastName);

        return $params;
    }

    private function getStripeCustomer($params = [])
    {
        $customer = new StripeCustomer([
            'id' => Arr::get($params, 'id', 'cus_'. Str::random(8)),
        ]);

        $default_source = 'card_' . Str::random(12);
        $customer->description = Arr::get($params, 'description', $this->faker->firstName.' '.$this->faker->lastName);
        $customer->email = Arr::get($params, 'email', $this->faker->unique()->safeEmail);
        $customer->default_source = null;
        $customer->created = time();
        $customer->metadata = [];

        return $customer;
    }
}
