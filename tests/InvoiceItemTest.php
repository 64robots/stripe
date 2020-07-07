<?php

namespace R64\Stripe\Tests;

use Illuminate\Support\Str;

class InvoiceItemTest extends TestCase
{
    /** @test */
    public function can_create_invoiceitem()
    {
        $invoiceItem = $this->processor->createInvoiceItem([
            'customer' => 'cus_' . Str::random(12),
            'subscription' => 'sub_' . Str::random(12),
            'amount' => '100',
            'currency' => 'usd',
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\InvoiceItem', get_class($invoiceItem));
    }
}
