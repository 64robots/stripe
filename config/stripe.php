<?php

return [

    /**
     * Indicate whether to ran stripe in sandbox mode
     */
    'sandbox' => env('STRIPE_SANDBOX', true),

    /**
     * Stripe production public key
     */
    'prod_public_key' => env('STRIPE_PROD_PUBLIC_KEY'),

    /**
     * Stripe production secret key
     */
    'prod_secret_key' => env('STRIPE_PROD_SECRET_KEY'),

    /**
     * Stripe sandbox public key
     */
    'sandbox_public_key' => env('STRIPE_SANDBOX_PUBLIC_KEY'),

    /**
     * Stripe sandbox secret key
     */
    'sandbox_secret_key' => env('STRIPE_SANDBOX_SECRET_KEY'),

    /**
     * Stripe connect id
     */
    'connect_id' => env('STRIPE_CONNECT_ID'),

    /**
     * Skip stripe connect
     */
    'skip_connect' => env('STRIPE_SKIP_CONNECT'),
];