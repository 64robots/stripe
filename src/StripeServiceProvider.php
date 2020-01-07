<?php

namespace R64\Stripe;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(StripeInterface::class, function ($app) {
            if ($app['config']->get('stripe.mock')) {
                return new MockHandler();
            }
    
            return new StripeHandler();
        });
    }
}
