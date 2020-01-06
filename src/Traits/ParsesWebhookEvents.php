<?php

namespace R64\Stripe\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ParsesWebhookEvents
{
    /***************************************************************************************
     ** CARD INFO
     ***************************************************************************************/

    /**
     * AddressFromSource
     * Note: The event format changes depending on the `object` value (source, card)
     * This assumes the source is always a credit card. It will have to be updated for ACH.
     * @param array StripeEvent
     */
    public static function getCardAddress(array $event)
    {
        if (self::sourceIsSourceObj($event)) {
            return [
                'address_line1' => Arr::get($event, 'data.object.source.owner.address.line1'),
                'address_line2' => Arr::get($event, 'data.object.source.owner.address.line2'),
                'city' => Arr::get($event, 'data.object.source.owner.address.city'),
                'region' => Arr::get($event, 'data.object.source.owner.address.state'),
                'postal_code' => Arr::get($event, 'data.object.source.owner.address.postal_code'),
                'country' => Arr::get($event, 'data.object.source.owner.address.country'),
            ];
        }
        // source is card obj
        return [
            'address_line1' => Arr::get($event, 'data.object.source.address_line1'),
            'address_line2' => Arr::get($event, 'data.object.source.address_line2'),
            'city' => Arr::get($event, 'data.object.source.address_city'),
            'region' => Arr::get($event, 'data.object.source.address_state'),
            'postal_code' => Arr::get($event, 'data.object.source.address_zip'),
            'country' => Arr::get($event, 'data.object.source.address_country'),
        ];
    }

    public static function getCardFullName(array $event)
    {
        if (self::sourceIsSourceObj($event)) {
            return Arr::get($event, 'data.object.source.owner.name');
        }

        return Arr::get($event, 'data.object.source.name');
    }

    public static function getCardFirstName(array $event)
    {
        return Str::before(self::getCardFullName($event), ' ');
    }

    public static function getCardLastName(array $event)
    {
        return Str::after(self::getCardFullName($event), ' ');
    }

    public static function getCardEmail(array $event)
    {
        if (self::sourceIsSourceObj($event)) {
            return Arr::get($event, 'data.object.source.owner.email');
        }
    }

    public static function getCardBrand(array $event)
    {
        if (self::sourceIs3DSecure($event)) {
            return Arr::get($event, 'data.object.source.three_d_secure.brand');
        }
        if (self::sourceIsSourceObj($event)) {
            return Arr::get($event, 'data.object.source.card.brand');
        }

        return Arr::get($event, 'data.object.source.brand');
    }

    public static function getLast4(array $event)
    {
        if (self::sourceIs3DSecure($event)) {
            return Arr::get($event, 'data.object.source.three_d_secure.last4');
        }
        if (self::sourceIsSourceObj($event)) {
            return Arr::get($event, 'data.object.source.card.last4');
        }

        return Arr::get($event, 'data.object.source.last4');
    }

    public static function getExpDate(array $event)
    {
        if (self::sourceIs3DSecure($event)) {
            $year = Arr::get($event, 'data.object.source.three_d_secure.exp_year');
            $month = Arr::get($event, 'data.object.source.three_d_secure.exp_month');

            return self::convertExpiration($year, $month);
        }
        if (self::sourceIsSourceObj($event)) {
            $year = Arr::get($event, 'data.object.source.card.exp_year');
            $month = Arr::get($event, 'data.object.source.card.exp_month');

            return self::convertExpiration($year, $month);
        }

        $year = Arr::get($event, 'data.object.source.exp_year');
        $month = Arr::get($event, 'data.object.source.exp_month');

        return self::convertExpiration($year, $month);
    }

    /***************************************************************************************
     ** PARSE SOURCE OBJECT TYPE
     ***************************************************************************************/

    public static function convertExpiration(string $year, string $month)
    {
        return Carbon::createFromFormat('Y-n-j', $year.'-'.$month.'-'. 1)->toDateString();
    }

    public static function sourceIs3DSecure(array $event)
    {
        if (Arr::get($event, 'data.object.source.type') === 'three_d_secure') {
            return true;
        }

        return false;
    }

    public static function sourceIsSourceObj(array $event)
    {
        if (Arr::get($event, 'data.object.source.object') === 'source') {
            return true;
        }

        return false;
    }

    public static function sourceIsCardObj(array $event)
    {
        if (Arr::get($event, 'data.object.source.object') === 'card') {
            return true;
        }

        return false;
    }
}
