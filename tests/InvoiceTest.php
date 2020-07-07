<?php

namespace R64\Stripe\Tests;

use Illuminate\Support\Str;

class InvoiceTest extends TestCase
{
    /** @test */
    public function can_get_invoice()
    {
        $invoice = $this->processor->getInvoice(1);
            
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Invoice', get_class($invoice));
    }

    /** @test */
    public function can_create_invoice()
    {
        $invoice = $this->processor->createInvoice([
            'customer' => 'cus_' . Str::random(12),
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Invoice', get_class($invoice));
    }
}
