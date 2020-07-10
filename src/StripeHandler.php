<?php

namespace R64\Stripe;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Stripe\Stripe;

class StripeHandler implements StripeInterface
{
    /**
     * Stripe connect id.
     *
     * @var string
     */
    private $stripeConnectId;

    /**
     * Determines whether to use stripe connect.
     *
     * @var string
     */
    private $skipConnect;

    public function __construct(array $options = [])
    {
        $this->setApiKey($options);

        $this->stripeConnectId = Arr::get($options, 'stripe_connect_id');
        $this->skipConnect = Arr::get($options, 'skip_stripe_connect', true);
    }

    public function __call($method, $arguments)
    {
        $match = $this->getMethodMatch($method);

        $methodName = preg_split("/{$match}/", $method, -1, PREG_SPLIT_NO_EMPTY)[0];

        return $this->makeRequest($methodName, $match, $arguments);
    }

    protected function getMethodMatch(string $method)
    {
        $availableMethods = collect(['list', 'get', 'create', 'update', 'delete']);

        $matches = $availableMethods->filter(function ($type) use ($method) {
            return Str::contains($method, $type);
        });

        // No match found
        if (! $matches->count()) {
            // throw an exception;- Method not match
        }

        return $matches->first();
    }

    protected function listResource(string $method, array $arguments)
    {
        $stripeResource = "Stripe\\{$method}";

        return $stripeResource::all($arguments[0], $this->stripeConnectParam());
    }

    protected function getResource(string $method, array $arguments)
    {
        $stripeResource = "Stripe\\{$method}";

        return $stripeResource::retrieve($arguments[0], $this->stripeConnectParam());
    }

    protected function createResource(string $method, array $arguments)
    {
        $stripeResource = "Stripe\\{$method}";

        return $stripeResource::create($arguments[0], $this->stripeConnectParam());
    }

    protected function updateResource(string $method, array $arguments)
    {
        $stripeResource = "Stripe\\{$method}";

        return $stripeResource::update($arguments[0], $arguments[1], $this->stripeConnectParam());
    }

    protected function deleteResource(string $method, array $arguments)
    {
        $stripeResource = "Stripe\\{$method}";

        return $stripeResource::delete($arguments[0]);
    }

    protected function makeRequest(string $method, string $match, array $arguments)
    {
        switch ($match) {
            case 'list':
                return $this->listResource($method, $arguments);

            case 'get':
                return $this->getResource($method, $arguments);

            case 'create':
                return $this->createResource($method, $arguments);

            case 'update':
                return $this->updateResource($method, $arguments);

            case 'delete':
                return $this->deleteResource($method, $arguments);
        }
    }

    private function setApiKey(array $options)
    {
        $secret = Arr::has($options, 'secret_key') ? $options['secret_key'] : config('stripe.secret');
        Stripe::setApiKey($secret);
    }

    private function stripeConnectParam()
    {
        if ($this->skipConnect) {
            return;
        }

        return ['stripe_account' => $this->stripeConnectId];
    }
}
