<?php

namespace R64\Stripe;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(StripeInterface::class, function ($app) {

            $options['secret_key'] = $app['config']->get('stripe.secret');
            $options['stripe_connect_id'] = $app->request->get('stripe_connect_id');
            $options['skip_stripe_connect'] = $app->request->get('skip_stripe_connect', true);

            if ($app['config']->get('stripe.mock')) {
                return new MockHandler($options);
            }
    
            return new StripeHandler($options);
        });
    }
}
