<?php

namespace R64\Stripe\Mocks;

use Faker\Factory;

class WooCommerceChargeEvent
{
    public static function create(string $email = null)
    {
        $faker = Factory::create();

        return [
            'created' => 1539567234,
            'livemode' => false,
            'id' => 'charge.succeeded_00000000000000',
            'type' => 'charge.succeeded',
            'object' => 'event',
            'request' => null,
            'pending_webhooks' => 1,
            'api_version' => '2018-09-24',
            // 'account' => 'acct_00000000000000',
            'data' => [
                'object' => [
                    'id' => 'ch_1DLKxyKwliJHFNUecQ4w1qdG',
                    'object' => 'charge',
                    'amount' => 37700,
                    'amount_refunded' => 0,
                    'application' => null,
                    'application_fee' => null,
                    'balance_transaction' => 'txn_1DLKy0KwliJHFNUez67vftQT',
                    'captured' => true,
                    'created' => 1539567234,
                    'currency' => 'usd',
                    'customer' => null,
                    'description' => 'ADRA Gift Catalog - Order 10215',
                    'destination' => null,
                    'dispute' => null,
                    'failure_code' => null,
                    'failure_message' => null,
                    'fraud_details' => [
                    ],
                    'invoice' => null,
                    'livemode' => true,
                    'metadata' => [
                        'customer_name' => 'Shannon Egan',
                        'customer_email' => $email ?: $faker->email,
                        'order_id' => '10215',
                    ],
                    'on_behalf_of' => null,
                    'order' => null,
                    'outcome' => [
                        'network_status' => 'approved_by_network',
                        'reason' => null,
                        'risk_level' => 'normal',
                        'seller_message' => 'Payment complete.',
                        'type' => 'authorized',
                    ],
                    'paid' => true,
                    'payment_intent' => null,
                    'receipt_email' => null,
                    'receipt_number' => null,
                    'refunded' => false,
                    'refunds' => [
                      'object' => 'list',
                      'data' => [
                      ],
                      'has_more' => false,
                      'total_count' => 0,
                      'url' => '/v1/charges/ch_1DLKxyKwliJHFNUecQ4w1qdG/refunds',
                    ],
                    'review' => null,
                    'shipping' => null,
                    'source' => [
                        'id' => 'src_1DLKxwKwliJHFNUel3ZQKaBb',
                        'object' => 'source',
                        'amount' => null,
                        'card' => [
                            'exp_month' => 5,
                            'exp_year' => 2021,
                            'address_line1_check' => 'pass',
                            'address_zip_check' => 'pass',
                            'brand' => 'Visa',
                            'country' => 'US',
                            'cvc_check' => 'pass',
                            'fingerprint' => 'sJTi2NUdz4C3cu2Y',
                            'funding' => 'debit',
                            'last4' => '1802',
                            'three_d_secure' => 'optional',
                            'name' => null,
                            'tokenization_method' => null,
                            'dynamic_last4' => null,
                        ],
                        'client_secret' => 'src_client_secret_Dmun1jPQMxevAnZTQ4xQGAQ5',
                        'created' => 1539567236,
                        'currency' => null,
                        'flow' => 'none',
                        'livemode' => true,
                        'metadata' => [
                        ],
                        'owner' => [
                            'address' => [
                                'city' => 'DRYDEN',
                                'country' => 'US',
                                'line1' => '4260 HAVENS ROAD',
                                'line2' => '',
                                'postal_code' => '48428',
                                'state' => 'MI',
                            ],
                            'email' => $email ?: $faker->email,
                            'name' => 'Shannon Egan',
                            'phone' => '2488249084',
                            'verified_address' => null,
                            'verified_email' => null,
                            'verified_name' => null,
                            'verified_phone' => null,
                        ],
                        'statement_descriptor' => null,
                        'status' => 'chargeable',
                        'type' => 'card',
                        'usage' => 'reusable',
                    ],
                    'source_transfer' => null,
                    'statement_descriptor' => 'ADRA GiftC',
                    'status' => 'succeeded',
                    'transfer_group' => null,
                ],
                'previous_attributes' => null,
            ],
        ];
    }
}
