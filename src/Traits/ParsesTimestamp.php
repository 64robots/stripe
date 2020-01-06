<?php

namespace R64\Stripe\Traits;

use Carbon\Carbon;

trait ParsesTimestamp
{
    public function carbonFromTimestamp($timestamp = null)
    {
        if (! $timestamp) {
            return;
        }

        return Carbon::createFromTimestamp($timestamp);
    }
}
