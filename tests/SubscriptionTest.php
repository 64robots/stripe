<?php

namespace R64\Stripe\Tests;

class SubscriptionTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_product()
    {
        $product = $this->processor->createProduct([
            'name' => $this->faker->name,
            'type' => $this->faker->word
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Product', get_class($product));
    }

    /**
     * @test
     */
    public function can_create_plan()
    {
        $plan = $this->processor->createPlan([
            'product' => $this->faker->word,
            'nickname' => $this->faker->name,
            'interval' => $this->faker->randomElement(['day', 'week', 'month', 'year']),
            'billing_scheme' => $this->faker->randomElement(['per_unit', 'tiered']),
            'amount' => 100,
            'currency' => 'usd'
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Plan', get_class($plan));
    }

    /**
     * @test
     */
    public function can_create_subscription()
    {
        $subscription = $this->processor->createSubscription([
            'customer' => "cus_{$this->faker->word}",
            'items' => [
                [
                    "object" => "list",
                    "plan" => $this->faker->word
                ]
            ]
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Subscription', get_class($subscription));
    }

    /**
     * @test
     */
    public function can_create_invoice()
    {
        $invoice = $this->processor->createInvoice([
            'customer' => "cus_{$this->faker->word}",
            'subscription' => "sub_{$this->faker->word}",
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Invoice', get_class($invoice));
    }

    /**
     * @test
     */
    public function can_create_invoice_item()
    {
        $invoiceItem = $this->processor->createInvoiceItem([
            'customer' => "cus_{$this->faker->word}",
            'subscription' => "sub_{$this->faker->word}",
            'amount' => 100,
            'currency' => 'usd',
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\InvoiceItem', get_class($invoiceItem));
    }

    /**
     * @test
     */
    public function can_get_an_invoice()
    {
        $invoice = $this->processor->getInvoice(1);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Invoice', get_class($invoice));
    }

    /**
     * @test
     */
    public function can_get_a_subscription()
    {
        $subscription = $this->processor->getSubscription(1);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Subscription', get_class($subscription));
    }
}
