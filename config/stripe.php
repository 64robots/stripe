<?php

return [

    /**
     * Indicates whether to mock stripe calls
     */
    'mock' => env('MOCK_PAYMENTS', true),

    /**
     * Indicates whether to ran stripe in sandbox mode
     */
    'sandbox' => env('STRIPE_SANDBOX', true),

    /**
     * Stripe public key
     */
    'key' => env('STRIPE_SANDBOX', true) ? env('STRIPE_SANDBOX_PUBLIC_KEY') : env('STRIPE_PROD_PUBLIC_KEY'),

    /**
     * Stripe secret key
     */
    'secret' => env('STRIPE_SANDBOX', true) ? env('STRIPE_SANDBOX_SECRET_KEY') : env('STRIPE_PROD_SECRET_KEY'),

    /**
     * Stripe connect id
     */
    'connect_id' => env('STRIPE_CONNECT_ID'),

    /**
     * Skip stripe connect
     */
    'skip_connect' => env('STRIPE_SKIP_CONNECT'),
];