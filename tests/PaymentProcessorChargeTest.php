<?php

namespace R64\Stripe\Tests;

class PaymentProcessorChargeTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_charge()
    {
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
    public function can_create_connect_charge()
    {
        $this->processor->createConnectCharge([
            'customer' => 1,
            'amount' => 10,
            'currency' => 'usd',
            'source' => 'tok_visa',
            'description' => 'a description',
            'transfer_data' => [
                'amount' => 8,
                'destination' => 2,
            ]
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }
}
