<?php

namespace R64\Stripe\Tests;

class CardHolderTest extends TestCase
{
    /** @test */
    public function can_get_cardholder()
    {
        $cardHolder = $this->processor->getCardHolder(1);
            
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\CardHolder', get_class($cardHolder));
    }

    /** @test */
    public function can_create_cardholder()
    {
        $cardHolder = $this->processor->createCardHolder([
            'billing' => [],
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['individual', 'company']),
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\CardHolder', get_class($cardHolder));
    }

    /** @test */
    public function can_update_cardholder()
    {
        $cardHolder = $this->processor->updateCardHolder(1, [
            'id' => 1,
            'metadata' => null,
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\CardHolder', get_class($cardHolder));
    }
}
