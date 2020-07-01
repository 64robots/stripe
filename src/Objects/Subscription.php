<?php

namespace R64\Stripe\Objects;

use R64\Stripe\Traits\ParsesTimestamp;
use Stripe\Subscription as StripeSubscription;

class Subscription
{
    use ParsesTimestamp;

    public $id;

    public $plan_id;

    public $customer_id;

    public $amount;

    public $billing;

    public $days_until_due;

    public $discount;

    public $cancel_at_period_end;

    public $billing_cycle_anchor;

    public $ended_at;

    public $canceled_at;

    public $current_period_end;

    public $current_period_start;

    public $created;

    public $latest_invoice_id;

    public function __construct($subscription = null)
    {
        if ($subscription && get_class($subscription) === 'Stripe\Subscription') {
            $this->setFromStripe($subscription);
        }
    }

    public function setFromStripe(StripeSubscription $subscription)
    {
        $this->id = $subscription->id;
        $this->plan_id = $subscription->plan ? $subscription->plan->id : null;
        $this->customer_id = $subscription->customer;
        $this->amount = $subscription->plan ? $subscription->plan->amount * $subscription->quantity : null;

        $this->billing = $subscription->billing; // `charge_automatically`
        $this->days_until_due = $subscription->days_until_due;
        $this->discount = $subscription->discount;

        // boolean
        $this->cancel_at_period_end = $subscription->cancel_at_period_end;

        // dates
        $this->billing_cycle_anchor = $this->carbonFromTimestamp($subscription->billing_cycle_anchor);
        $this->ended_at = $this->carbonFromTimestamp($subscription->ended_at);
        $this->canceled_at = $this->carbonFromTimestamp($subscription->canceled_at);
        $this->current_period_end = $this->carbonFromTimestamp($subscription->current_period_end);
        $this->current_period_start = $this->carbonFromTimestamp($subscription->current_period_start);
        $this->created = $this->carbonFromTimestamp($subscription->created);
        $this->latest_invoice_id = $subscription->latest_invoice;
    }
}
