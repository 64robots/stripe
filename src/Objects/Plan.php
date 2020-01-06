<?php

namespace R64\Stripe\Objects;

use Carbon\Carbon;
use Stripe\Plan as StripePlan;

class Plan
{
    public $id;

    public $product_id;

    public $nickname;

    public $amount;

    public $interval_count;

    public $interval;

    public $billing_scheme;

    public $usage_type;

    public $currency;

    public $created;

    public function __construct($plan = null)
    {
        if ($plan && get_class($plan) === 'Stripe\Plan') {
            $this->setFromSTripe($plan);
        }
    }

    public function setFromStripe(StripePlan $plan)
    {
        $this->id = $plan->id;
        $this->product_id = $plan->product;
        $this->nickname = $plan->nickname;
        $this->amount = $plan->amount;
        $this->interval_count = $plan->interval_count;
        $this->interval = $plan->interval;
        $this->billing_scheme = $plan->billing_scheme;
        $this->usage_type = $plan->usage_type;
        $this->currency = $plan->currency;
        $this->created = Carbon::createFromTimestamp($plan->created);
    }
}
