<?php

namespace R64\Stripe\Adapters;

use Carbon\Carbon;

class Customer
{
    public $id;

    public $description;

    public $email;

    public $default_source;

    public $created;

    public function __construct($customer)
    {
        if ($customer && get_class($customer) === 'Stripe\Customer') {
            $this->setFromSTripe($customer);
        }
    }

    public function setFromStripe(\Stripe\Customer $customer)
    {
        $this->id = $customer->id;
        $this->description = $customer->description;
        $this->email = $customer->email;
        $this->default_source = $customer->default_source;
        $this->metadata = $customer->metadata;
        $this->created = Carbon::createFromTimestamp($customer->created);
    }
}
