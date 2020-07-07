<?php

namespace R64\Stripe\Tests;

use Illuminate\Support\Str;

class PlanTest extends TestCase
{
     /** @test */
     public function can_create_plan()
     {
         $plan = $this->processor->createPlan([
            'amount' => '1000',
            'currency' => 'usd',
            'interval' => $this->faker->randomElement(['day', 'week', 'month', 'year']),
            'product' => 'prod_' . Str::random(12),
         ]);
 
         $this->assertTrue($this->processor->attemptSuccessful());
         $this->assertEquals('', $this->processor->getErrorMessage());
         $this->assertEquals('', $this->processor->getErrorType());
         $this->assertEquals('R64\Stripe\Adapters\Plan', get_class($plan));
     }
}
