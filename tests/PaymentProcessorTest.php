<?php

namespace R64\Stripe\Tests;

use Faker\Factory;
use R64\Stripe\PaymentProcessor;
use Orchestra\Testbench\TestCase;
use R64\Stripe\StripeServiceProvider;

class PaymentProcessorTest extends TestCase
{
    public $processor;

    public $faker;

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('stripe.mock', true);
    }

    protected function getPackageProviders($app)
    {
        return [StripeServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app[PaymentProcessor::class];
        $this->faker = Factory::create();
    }

    /**
     * @test
     */
    public function can_charge_stripe_account()
    {
        $this->assertTrue(true);

        $this->processor->createCharge([
            'customer' => 1,
            'amount' => 10,
            'currency' => 'usd',
            'source' => 'tok_visa'
        ]);
        
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }

    /**
     * @test
     */
    public function can_create_customer()
    {   
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastname;
        $email = $this->faker->safeEmail;
        $description = "{$firstName} {$lastName}";

        $this->processor->createCustomer([
            'description' => $description,
            'source' => 'tok_visa',
            'email' => $email,
            'metadata' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]
        ]);
        
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }

    /**
     * @test
     */
    public function can_update_customer()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastname;
        $email = $this->faker->safeEmail;
        $description = "{$firstName} {$lastName}";

        $this->processor->updateCustomer([
            'id' => 1,
            'email' => $email,
            'description' => $description,
            'source' => 'tok_visa'
        ]);
        
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }

    /**
     * @test
     */
    public function can_get_a_customer()
    {
        $this->processor->getCustomer(1);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }
}
