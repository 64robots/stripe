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
        $this->assertEquals(1500, $balance->getAvailable('USD'));
        $this->assertEquals(1000, $balance->getPending('USD'));
    }

    /**
     * @test
     */
    public function can_get_connect_balance_details()
    {
        $stripeAccountId = 'acct_1';
        $balance = $this->processor->getConnectBalance($stripeAccountId);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Objects\Balance', get_class($balance));
    }
}
