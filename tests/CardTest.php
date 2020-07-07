<?php

namespace R64\Stripe\Tests;

class CardTest extends TestCase
{
    /** @test */
    public function can_create_card()
    {
        $card = $this->processor->createCard([
            'source' => 'tok_visa'
        ]);
            
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Card', get_class($card));

    }

    /** @test */
    public function can_get_card_details()
    {
        $card = $this->processor->getCard(1, 1);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Card', get_class($card));
    }

    /** @test */
    public function can_update_card()
    {
        $card = $this->processor->updateCard(1, 1, []);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Card', get_class($card));
    }
}
