<?php

namespace R64\Stripe\Tests;

class CustomerTest extends TestCase
{
    /** @test */
    public function can_create_customer()
    {   
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastname;
        $email = $this->faker->safeEmail;
        $description = "{$firstName} {$lastName}";

        $this->processor->createCustomer([
            'description' => $description,
            'source' => 'tok_visa',
            'email' => $email,
            'metadata' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]
        ]);
        
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }

    /** @test */
    public function can_update_customer()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastname;
        $email = $this->faker->safeEmail;
        $description = "{$firstName} {$lastName}";

        $this->processor->updateCustomer([
            'id' => 1,
            'email' => $email,
            'description' => $description,
            'source' => 'tok_visa'
        ]);
        
        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }

    /** @test */
    public function can_get_a_customer()
    {
        $this->processor->getCustomer(1);

        $this->assertTrue($this->processor->attemptSuccessful());
        $this->assertEquals('', $this->processor->getErrorMessage());
        $this->assertEquals('', $this->processor->getErrorType());
    }
}
