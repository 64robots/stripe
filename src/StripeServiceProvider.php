<?php

namespace R64\Stripe;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/stripe.php' => config_path('stripe.php'),
        ], 'config');
    }

    public function register()
    {
        $this->app->bind(StripeInterface::class, function ($app) {
            $options['secret_key'] = $app['config']->get('stripe.prod_secret_key');
            $options['stripe_connect_id'] = $app['config']->get('stripe.connect_id') ?? $app->request->get('stripe_connect_id');
            $options['skip_stripe_connect'] = $app['config']->get('stripe.skip_connect') ?? $app->request->get('skip_stripe_connect', true);

            if ($app['config']->get('stripe.sandbox')) {
                $options['secret_key'] = $app['config']->get('stripe.sandbox_secret_key');
            }

            if ($app['config']->get('stripe.mock')) {
                return new MockHandler($options);
            }
    
            return new StripeHandler($options);
        });
    }
}
