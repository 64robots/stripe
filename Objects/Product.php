<?php

namespace App\Integrations\Stripe\Objects;

use Carbon\Carbon;
use Stripe\Product as StripeProduct;

class Product
{
    public $id;

    public $name;

    public $description;

    public $email;

    public $created;

    public function __construct($product = null)
    {
        if ($product && get_class($product) === 'Stripe\Product') {
            $this->setFromSTripe($product);
        }
    }

    public function setFromStripe(StripeProduct $product)
    {
        $this->id = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->created = Carbon::createFromTimestamp($product->created);
    }
}
