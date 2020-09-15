<?php

namespace R64\Stripe\Tests;

class PaymentProcessorPayoutTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_payout()
    {
        $payout = $this->processor->createPayout([
            'amount' => 1100,
            'currency' => 'usd',
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Objects\Payout', get_class($payout));
    }

    /**
     * @test
     */
    public function can_create_connect_payout()
    {
        $stripeAccountId = 'acct_1';
        $payout = $this->processor->createConnectPayout([
            'amount' => 1100,
            'currency' => 'usd',
        ], $stripeAccountId);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Objects\Payout', get_class($payout));
    }
}
