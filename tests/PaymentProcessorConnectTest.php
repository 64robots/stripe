<?php

namespace R64\Stripe\Tests;

class PaymentProcessorConnectTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_account_details()
    {
        $stripeAccountId = 'acct_1';
        $account = $this->processor->getConnectAccount($stripeAccountId);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Objects\Account', get_class($account));
    }
}
