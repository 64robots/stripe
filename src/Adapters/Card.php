<?php

namespace R64\Stripe\Adapters;

use Carbon\Carbon;

class Card
{
    public $id;

    public $object;

    public $name;

    public $brand;

    // input
    public $country;

    public $last4;

    public $exp_month;

    public $exp_year;

    public $exp_date;

    // address
    public $address_line1;

    public $address_line2;

    public $address_city;

    public $address_state;

    public $address_zip;

    public $address_country;

    // other
    public $address_zip_check;

    public $cvc_check;

    public $dynamic_last4;

    public $fingerprint;

    public $funding;

    public $metadata;

    public $tokenization_method;

    public $customer;

    public function __construct($card = null)
    {
        if ($card && (get_class($card) === 'Stripe\Card' || getProperty($card, 'object') == 'card')) {
            $this->setFromSTripe($card);
        }
    }

    public function setFromStripe($card)
    {
        $this->id = $card->id;
        $this->object = $card->object;
        $this->name = $card->name;
        $this->brand = $card->brand;
        $this->last4 = $card->last4;
        $this->exp_month = $card->exp_month;
        $this->exp_year = $card->exp_year;
        $this->country = $card->country;

        $this->address_line1 = data_get($card, 'address_line1');
        $this->address_line2 = data_get($card, 'address_line2');
        $this->address_city = data_get($card, 'address_city');
        $this->address_state = data_get($card, 'address_state');
        $this->address_zip = data_get($card, 'address_zip');
        $this->address_country = data_get($card, 'address_country');

        $this->address_zip_check = data_get($card, 'address_zip_check');
        $this->cvc_check = data_get($card, 'cvc_check');
        $this->dynamic_last4 = data_get($card, 'dynamic_last4');
        $this->fingerprint = data_get($card, 'fingerprint');
        $this->funding = data_get($card, 'funding');
        $this->metadata = data_get($card, 'metadata');
        $this->tokenization_method = data_get($card, 'tokenization_method');

        $this->exp_date = Carbon::createFromFormat('Y-n-j', $card->exp_year.'-'.$card->exp_month.'-'. 1)->toDateString();
    }

    public function isSource()
    {
        return $this->object === 'source';
    }

    public function isCard()
    {
        return $this->object === 'card';
    }
}
