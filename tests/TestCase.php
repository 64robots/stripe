<?php

namespace R64\Stripe\Tests;

use Faker\Factory;
use R64\Stripe\StripeServiceProvider;
use R64\Stripe\Processor\StripeProcessor;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public $processor;

    public $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app[StripeProcessor::class];

        $this->faker = Factory::create();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('stripe.mock', true);
    }

    protected function getPackageProviders($app)
    {
        return [StripeServiceProvider::class];
    }
}
