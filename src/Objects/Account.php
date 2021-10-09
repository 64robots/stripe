<?php

namespace R64\Stripe\Objects;

use Carbon\Carbon;

class Account
{
    public $id;

    public $object;

    public $email;

    public $name;

    public $support_phone;

    public $support_url;

    public $url;

    public $business_type;

    public $created;

    public function __construct($account)
    {
        if ($account && get_class($account) === 'Stripe\Account') {
            $this->setFromSTripe($account);
        }
    }

    public function setFromStripe(\Stripe\Account $account)
    {
        $this->id = $account->id;
        $this->object = $account->object;
        $this->email = $account->email;
        $this->name = $account->business_profile->name;
        $this->support_phone = $account->business_profile->support_phone;
        $this->support_url = $account->business_profile->support_url;
        $this->url = $account->business_profile->url;
        $this->business_type = $account->business_type;
        $this->created = Carbon::createFromTimestamp($account->created);
    }
}
