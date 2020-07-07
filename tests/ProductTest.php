<?php

namespace R64\Stripe\Tests;

class ProductTest extends TestCase
{
    /** @test */
    public function can_create_product()
    {
        $product = $this->processor->createProduct([
            'name' => $this->faker->name,
            'type' => $this->faker->word
        ]);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
        $this->assertEquals('R64\Stripe\Adapters\Product', get_class($product));
    }
}
