<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Faker\Factory;
use R64\Stripe\Objects\Product;
use Stripe\Product as StripeProduct;

trait ProductMock
{
    public function createProduct(array $params)
    {
        $stripeProduct = $this->getMockStripeProduct('create-product', $params);
        $product = $stripeProduct::create($params, $this->stripeConnectParam());

        m::close();
        
        if ($product) {
            return new Product($product);
        }
    }

    protected function getMockStripeProduct(string $slug, $params = [])
    {
        $product = m::mock('alias:StripeProduct');

        switch ($slug) {
            case 'create-product':
                $product
                    ->shouldReceive('create')
                    ->with([
                        'name' => $params['name'],
                        'type' => $params['type'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeProduct($params));

                break;
        }

        $this->successful = true;

        return $product;
    }

    protected function getStripeProduct($params = [])
    {
        $product = new StripeProduct(['id' => 1]);

        $faker = Factory::create();
        $product->name = count($params) ? $params['name'] : $faker->word;
        $product->description = $faker->paragraph;
        $product->created = time();

        return $product;
    }
}
