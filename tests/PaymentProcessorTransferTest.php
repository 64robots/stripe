<?php

namespace R64\Stripe\Tests;

class PaymentProcessorTransferTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_connect_transfer()
    {
        $this->processor->createConnectTransfer([
            'amount' => 100,
            'currency' => 'USD',
            'source_transaction' => 'ch_123456',
            'destination' => 'acc_1234568',
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }
}
