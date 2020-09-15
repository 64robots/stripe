<?php

namespace R64\Stripe\Tests;

class PaymentProcessorBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_balance_details()
    {
        $balance = $this->processor->getBalance();

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Objects\Balance', get_class($balance));
    }
}
