<?php

namespace R64\Stripe;

use R64\Stripe\Objects\Balance;
use R64\Stripe\Objects\Card;
use R64\Stripe\Objects\CardHolder;
use R64\Stripe\Objects\Charge;
use R64\Stripe\Objects\Customer;
use R64\Stripe\Objects\Invoice;
use R64\Stripe\Objects\InvoiceItem;
use R64\Stripe\Objects\Plan;
use R64\Stripe\Objects\Product;
use R64\Stripe\Objects\Subscription;
use R64\Stripe\Objects\Token;
use App\Models\Donation;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mockery as m;
use Stripe\Card as StripeCard;
use Stripe\Charge as StripeCharge;
use Stripe\Customer as StripeCustomer;
use Stripe\Invoice as StripeInvoice;
use Stripe\InvoiceItem as StripeInvoiceItem;
use Stripe\Issuing\Cardholder as StripeCardHolder;
use Stripe\Plan as StripePlan;
use Stripe\Product as StripeProduct;
use Stripe\Balance as StripeBalance;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Token as StripeToken;

class MockHandler implements StripeInterface
{
    use ResponseStatusTrait;

    /**
     * Organization stripe connect id.
     *
     * @var string
     */
    private $stripeConnectId;

    private $skipConnect;

    private $currency;

    public function __construct(array $options = [])
    {
        $secret = Arr::has($options, 'secret_key') ? $options['secret_key'] : config('stripe.secret');
        Stripe::setApiKey($secret);

        $this->stripeConnectId = Arr::get($options, 'stripe_connect_id');
        $this->skipConnect = Arr::get($options, 'skip_stripe_connect', true);
    }

    /*********************************************************************************/

    /** CHARGE
     **********************************************************************************/
    public function createCharge(array $params)
    {
        $stripeCharge = $this->getMockStripeCharge($params);
        $charge = $stripeCharge::create($params, $this->stripeConnectParam());

        m::close();

        if ($charge) {
            return new Charge($charge);
        }
    }

    public function createConnectCharge(array $params)
    {
        $stripeCharge = $this->getMockStripeConnectCharge($params);
        $charge = $stripeCharge::create($params, $this->stripeConnectParam());

        m::close();

        if ($charge) {
            return new Charge($charge);
        }
    }

    public function listCharges(array $params)
    {
        $handler = m::mock(self::class);

        $handler
            ->shouldReceive('autoPagingIterator')
            ->andReturn([
                (object) [
                    'status' => 'failed',
                    'id' => optional(Donation::first())->stripe_charge_id,
                ],
                (object) [
                    'status' => 'success',
                    'id' => 'ch_1',
                ],
            ]);

        return $handler;
    }

    /*********************************************************************************/

    /** CUSTOMER
     **********************************************************************************/
    public function createCustomer(array $params)
    {
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $customer = $stripeCustomer::create($params, $this->stripeConnectParam());

        m::close();

        if ($customer) {
            return new Customer($customer);
        }
    }

    public function listCustomers(array $params)
    {
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $result = $stripeCustomer::all($params, $this->stripeConnectParam());

        m::close();

        if ($result) {
            return collect($result->data);
        }

        return collect([]);
    }

    public function getCustomer(string $id)
    {
        $stripeCustomer = $this->getMockStripeCustomer(['id' => $id]);
        $customer = $stripeCustomer::retrieve($id, $this->stripeConnectParam());

        m::close();

        if ($customer) {
            return new Customer($customer);
        }
    }

    public function updateCustomer(array $params)
    {
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $customers = $this->listCustomers(['email' => $params['email']]);

        if (! $customers->count() == 1) {
            abort(400, 'Cannot update customer: '.$customers->count());
        }
        $customer = $customers->first();
        $stripeCustomer = $this->getMockStripeCustomer($params);
        $updatedCustomer = $stripeCustomer::update(
            $params['id'],
            ['description' => $params['description']],
            $this->stripeConnectParam()
        );

        m::close();

        if ($updatedCustomer) {
            return new Customer($updatedCustomer);
        }
    }

    /*********************************************************************************/

    /** INVOICE
     **********************************************************************************/
    public function createInvoiceItem(array $params)
    {
        $stripeInvoiceItem = $this->getMockStripeInvoiceItem($params);
        $invoiceItem = $stripeInvoiceItem::create($params, $this->stripeConnectParam());

        m::close();

        if ($invoiceItem) {
            return new InvoiceItem($invoiceItem);
        }
    }

    public function createInvoice(array $params)
    {
        $stripeInvoice = $this->getMockStripeInvoice($params);
        $invoice = $stripeInvoice::create($params, $this->stripeConnectParam());

        m::close();

        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    public function getInvoice(string $id)
    {
        $stripeInvoice = $this->getMockStripeInvoice();
        $invoice = $stripeInvoice::retrieve($id, $this->stripeConnectParam());

        m::close();

        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    /*********************************************************************************/

    /** SUBSCRIPTIONS
     **********************************************************************************/
    public function createSubscription(array $params)
    {
        $stripeSubscription = $this->getMockStripeSubscription('create-subscription', $params);
        $subscription = $stripeSubscription::create($params, $this->stripeConnectParam());

        m::close();

        if ($subscription) {
            return new Subscription($subscription);
        }
    }

    public function getSubscription(string $id)
    {
        $stripeSubscription = $this->getMockStripeSubscription('retrieve-subscription');
        $subscription = $stripeSubscription::retrieve($id, $this->stripeConnectParam());

        m::close();

        if ($subscription) {
            return new Subscription($subscription);
        }
    }

    public function createProduct(array $params)
    {
        $stripeProduct = $this->getMockStripeProduct('create-product', $params);
        $product = $stripeProduct::create($params, $this->stripeConnectParam());

        m::close();

        if ($product) {
            return new Product($product);
        }
    }

    public function createPlan(array $params)
    {
        $stripePlan = $this->getMockStripePlan('create-plan', $params);
        $plan = $stripePlan::create($params, $this->stripeConnectParam());

        m::close();

        if ($plan) {
            return new Plan($plan);
        }
    }

    /*********************************************************************************
     ** CARD
     *********************************************************************************/

    public function getCard(string $customerId, string $cardId)
    {
        $stripeCardClass = $this->getMockStripeCard('retrieve', ['id' => $cardId]);
        $card = $stripeCardClass::retrieve($cardId, $this->stripeConnectParam());

        m::close();

        if ($card) {
            return new Card($card);
        }
    }

    public function createCard(array $params)
    {
        $card = $this->getMockStripeCard('create', $params);

        m::close();

        if ($card) {
            return new Card($card);
        }
    }

    public function updateCard(string $customerId, string $cardId, array $params)
    {
        $card = $this->getMockStripeCard('update', $params);

        m::close();

        if ($card) {
            return new Card($card);
        }
    }

    /***************************************************************************************
     ** CARD HOLDER
     ***************************************************************************************/

    public function getCardHolder(string $id)
    {
        $cardHolderClass = $this->getMockStripeCardHolder('retrieve', ['id' => $id]);
        $cardHolder = $cardHolderClass::retrieve(['id' => $id], $this->stripeConnectParam());

        m::close();

        if ($cardHolder) {
            return new CardHolder($cardHolder);
        }
    }

    public function createCardHolder(array $params)
    {
        $cardHolderClass = $this->getMockStripeCardHolder('create', $params);
        $cardHolder = $cardHolderClass::create($params, $this->stripeConnectParam());

        m::close();

        if ($cardHolder) {
            return new CardHolder($cardHolder);
        }
    }

    public function updateCardHolder(string $id, array $params)
    {
        $cardHolderClass = $this->getMockStripeCardHolder('update', $params);
        $cardHolder = $cardHolderClass::update($id, $params, $this->stripeConnectParam());

        m::close();

        if ($cardHolder) {
            return new CardHolder($cardHolder);
        }
    }

    /***************************************************************************************
     ** BALANCE
     ***************************************************************************************/

    public function getBalance()
    {
        $balanceClass = $this->getMockStripeBalance('retrieve');
        $balance = $balanceClass::retrieve();

        m::close();

        if ($balance) {
            return new Balance($balance);
        }
    }

    public function getConnectBalance($stripeAccountId)
    {
        $stripeConnect = [
            'stripe_account' => $stripeAccountId,
        ];
        $balanceClass = $this->getMockStripeBalance('retrieve-connect', $stripeConnect);
        $balance = $balanceClass::retrieve($stripeConnect);

        m::close();

        if ($balance) {
            return new Balance($balance);
        }
    }

    /*********************************************************************************
     ** TOKEN
     *********************************************************************************/

    public function getToken(string $id, $noWrapper = false)
    {
        $stripeToken = $this->getMockStripeToken('get-token', ['id' => $id]);
        $token = $stripeToken::retrieve($id, $this->stripeConnectParam());

        m::close();

        if ($token) {
            return $noWrapper ? $token : new Token($token);
        }
    }

    /*********************************************************************************/

    /** HELPERS
     **********************************************************************************/
    public function stripeConnectParam()
    {
        if ($this->skipConnect) {
            return;
        }

        return ['stripe_account' => $this->stripeConnectId];
    }

    /***************************************************************************************
     ** STRIPE CHARGE
     ***************************************************************************************/

    private function getMockStripeCharge($params)
    {
        $charge = m::mock('alias:StripeCharge');

        $charge
            ->shouldReceive('create')
            ->with([
                'customer' => $params['customer'],
                'amount' => $params['amount'],
                'currency' => $params['currency'],
                'source' => $params['source'],
            ], $this->stripeConnectParam())
            ->andReturn($this->getStripeCharge($params));

        $this->successful = true;

        return $charge;
    }

    private function getMockStripeConnectCharge($params)
    {
        $charge = m::mock('alias:StripeCharge');

        $charge
            ->shouldReceive('create')
            ->with([
                'customer' => $params['customer'],
                'amount' => $params['amount'],
                'currency' => $params['currency'],
                'source' => $params['source'],
                'description' => $params['description'],
                'transfer_data' => [
                    'amount' => $params['transfer_data']['amount'],
                    'destination' => $params['transfer_data']['destination'],
                ],
            ], $this->stripeConnectParam())
            ->andReturn($this->getStripeCharge($params));

        $this->successful = true;

        return $charge;
    }

    private function getStripeCharge($params)
    {
        $faker = Factory::create();

        $charge = new StripeCharge(['id' => 'ch_1']);
        $charge->amount = $params['amount'];
        $charge->currency = $params['currency'];
        $charge->created = time();
        $charge->source = (object) [
            'id' => 'card_1',
            'object' => 'card',
            'name' => $faker->name,
            'brand' => $faker->creditCardType,
            'last4' => '4242',
            'exp_month' => $faker->numberBetween(1, 12),
            'exp_year' => now()->addYear()->year,
            'country' => 'US',
        ];

        return $charge;
    }

    /***************************************************************************************
     ** STRIPE INVOICE
     ***************************************************************************************/

    private function getMockStripeInvoice($params = [])
    {
        $invoice = m::mock('alias:StripeInvoice');

        if (Arr::get($params, 'customer')) {
            $invoice
                ->shouldReceive('create')
                ->with([
                    'customer' => $params['customer'],
                    'subscription' => $params['subscription'],
                ], $this->stripeConnectParam())
                ->andReturn($this->getStripeInvoice());
        }

        $invoice
            ->shouldReceive('retrieve')
            ->andReturn($this->getStripeInvoice());

        $this->successful = true;

        return $invoice;
    }

    private function getStripeInvoice()
    {
        $invoice = new StripeInvoice(['id' => 1]);

        $faker = Factory::create();
        $invoice->amount_paid = $faker->numberBetween(100, 1000);
        $invoice->currency = 'usd';
        $invoice->subscription = 1;
        $invoice->receipt_number = $faker->randomNumber();
        $invoice->date = time();

        return $invoice;
    }

    /***************************************************************************************
     ** STRIPE INVOICE ITEM
     ***************************************************************************************/

    private function getMockStripeInvoiceItem($params)
    {
        $invoiceItem = m::mock('alias:StripeInvoiceItem');

        $invoiceItem
            ->shouldReceive('create')
            ->with([
                'customer' => $params['customer'],
                'amount' => $params['amount'],
                'subscription' => $params['subscription'],
                'currency' => $params['currency'],
            ], $this->stripeConnectParam())
            ->andReturn($this->getStripeInvoiceItem($params));

        $this->successful = true;

        return $invoiceItem;
    }

    private function getStripeInvoiceItem($params)
    {
        $invoiceItem = new StripeInvoiceItem(['id' => 1]);

        $invoiceItem->customer = $params['customer'];
        $invoiceItem->subscription = $params['subscription'];
        $invoiceItem->amount = $params['amount'];
        $invoiceItem->currency = $params['currency'];
        $invoiceItem->object = 'invoiceitem';
        $invoiceItem->invoice = null;
        $invoiceItem->date = time();

        return $invoiceItem;
    }

    /***************************************************************************************
     ** STRIPE SUBSCRIPTION
     ***************************************************************************************/

    private function getMockStripeSubscription(string $slug, $params = [])
    {
        $subscription = m::mock('alias:StripeSubscription');

        if (Arr::get($params, 'customer')) {
            $subscription
                ->shouldReceive('create')
                ->with([
                    'customer' => $params['customer'],
                    'items' => $params['items'],
                ], $this->stripeConnectParam())
                ->andReturn($this->getStripeSubscription($params));
        }

        $subscription
            ->shouldReceive('retrieve')
            ->andReturn($this->getStripeSubscription());

        $this->successful = true;

        return $subscription;
    }

    private function getStripeSubscription($params = [])
    {
        $subscription = new StripeSubscription(['id' => 'sub_1']);

        $faker = Factory::create();
        $subscription->plan = (object) [
            'id' => count($params) ? $params['items']['0']['plan'] : 'plan_'.Str::random(10),
            'amount' => 2500,
        ];
        $subscription->customer = count($params) ? $params['customer'] : 'cus_'.Str::random(10);
        $subscription->quantity = 1;
        $subscription->billing = $faker->numberBetween(10, 100);
        $subscription->discount = $faker->numberBetween(10, 100);
        $subscription->cancel_at_period_end = false;
        $subscription->billing_cycle_anchor = now()->addDays(10)->timestamp;
        $subscription->ended_at = now()->addDays(30)->timestamp;
        $subscription->canceled_at = now()->addDays(30)->timestamp;
        $subscription->current_period_end = now()->addDays(30)->timestamp;
        $subscription->current_period_start = time();
        $subscription->days_until_due = $faker->numberBetween(1, 30);
        $subscription->created = time();

        return $subscription;
    }

    /***************************************************************************************
     ** STRIPE CUSTOMER
     ***************************************************************************************/

    private function getMockStripeCustomer($params = [])
    {
        $customer = m::mock('alias:StripeCustomer');

        $customer
            ->shouldReceive('create')
            ->with($params, $this->stripeConnectParam())
            ->andReturn($this->getStripeCustomer($params));

        $customer
            ->shouldReceive('retrieve')
            ->with(Arr::get($params, 'id'), $this->stripeConnectParam())
            ->andReturn($this->getStripeCustomer());

        $customer
            ->shouldReceive('all')
            ->with([
                'email' => Arr::get($params, 'email'),
            ], $this->stripeConnectParam())
            ->andReturn((object) ['data' => $this->getStripeCustomer()]);

        $customer
            ->shouldReceive('update')
            ->with(
                1,
                ['description' => Arr::get($params, 'description')],
                $this->stripeConnectParam()
            )
            ->andReturn($this->getStripeCustomer(['description' => Arr::get($params, 'description'), 'email' => Arr::get($params, 'email')]));

        $this->successful = true;

        return $customer;
    }

    private function setParams(array $params)
    {
        $faker = Factory::create();
        $params['id'] = $params['id'] ?? 'cus_uRBZyegl'; // 'cus_' . Str::random(8);
        $params['description'] = $params['description'] ?? $faker->paragraph;
        $params['source'] = 'tok_visa';
        $params['email'] = $params['email'] ?? $faker->safeEmail;
        $params['metadata']['first_name'] = $params['metadata']['first_name'] ?? $faker->firstName;
        $params['metadata']['last_name'] = $params['metadata']['last_name'] ?? $faker->lastName;

        return $params;
    }

    private function getStripeCustomer($params = [])
    {
        $customer = new StripeCustomer([
            'id' => Arr::get($params, 'id', 'cus_'.Str::random(8)),
        ]);

        $faker = Factory::create();

        $default_source = 1;
        $customer->description = Arr::get($params, 'description', $faker->firstName.' '.$faker->lastName);
        $customer->email = Arr::get($params, 'email', $faker->unique()->safeEmail);
        $customer->default_source = null;
        $customer->created = time();
        $customer->metadata = [];

        return $customer;
    }

    /***************************************************************************************
     ** STRIPE PRODUCT
     ***************************************************************************************/

    private function getMockStripeProduct(string $slug, $params = [])
    {
        $product = m::mock('alias:StripeProduct');

        switch ($slug) {
            case 'create-product':
                $product
                    ->shouldReceive('create')
                    ->with([
                        'name' => $params['name'],
                        'type' => $params['type'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeProduct($params));

                break;
        }

        $this->successful = true;

        return $product;
    }

    private function getStripeProduct($params = [])
    {
        $product = new StripeProduct(['id' => 1]);

        $faker = Factory::create();
        $product->name = count($params) ? $params['name'] : $faker->word;
        $product->description = $faker->paragraph;
        $product->created = time();

        return $product;
    }

    /***************************************************************************************
     ** STRIPE PLAN
     ***************************************************************************************/

    private function getMockStripePlan(string $slug, $params = [])
    {
        $plan = m::mock('alias:StripePlan');

        switch ($slug) {
            case 'create-plan':
                $plan
                    ->shouldReceive('create')
                    ->with([
                        'product' => $params['product'],
                        'nickname' => $params['nickname'],
                        'interval' => $params['interval'],
                        'billing_scheme' => $params['billing_scheme'],
                        'amount' => $params['amount'],
                        'currency' => $params['currency'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripePlan($params));

                break;
        }

        $this->successful = true;

        return $plan;
    }

    private function getStripePlan($params = [])
    {
        $plan = new StripePlan(['id' => 1]);

        $faker = Factory::create();
        $plan->product = count($params) ? $params['product'] : 1;
        $plan->nickname = count($params) ? $params['nickname'] : $faker->word;
        $plan->amount = count($params) ? $params['amount'] : $faker->numberBetween(1000, 5000);
        $plan->interval = count($params) ? $params['interval'] : 'month';
        $plan->interval_count = $faker->numberBetween(1, 6);
        $plan->billing_scheme = count($params) ? $params['billing_scheme'] : 'per_unit';
        $plan->usage_type = $faker->word;
        $plan->currency = count($params) ? $params['currency'] : 'usd';
        $plan->created = time();

        return $plan;
    }

    /***************************************************************************************
     ** STRIPE TOKEN
     ***************************************************************************************/

    private function getMockStripeToken(string $slug, $params = [])
    {
        $token = m::mock('alias:StripeToken');

        switch ($slug) {
            case 'get-token':
                $token
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeToken());

                break;
        }

        $this->successful = true;

        return $token;
    }

    private function getStripeToken()
    {
        $token = new StripeToken(['id' => 'tok_1234']);

        $faker = Factory::create();
        $token->object = 'token';
        $token->type = 'card';
        $token->created = time();
        $token->card = $this->getStripeCard();

        return $token;
    }

    /***************************************************************************************
     ** STRIPE CARD
     ***************************************************************************************/

    private function getMockStripeCard(string $slug, $params = [])
    {
        $card = m::mock('alias:StripeCard');

        switch ($slug) {
            case 'retrieve':
                $card
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCard($params));

            break;

            case 'create':
                $card
                    ->shouldReceive('create')
                    ->with(['source' => Arr::get($params, 'source')], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCard());

            break;

            case 'update':
                $card
                    ->shouldReceive('update')
                    ->with($params, $this->stripeConnectParam())
                    ->andReturn($this->getStripeCard($params));

            break;
        }
        
        $this->successful = true;

        return $card;
    }

    /***************************************************************************************
     ** CARDHOLDER
     ***************************************************************************************/

    private function getMockStripeCardHolder(string $slug, $params = [])
    {
        $cardHolder = m::mock('alias:StripeCardHolder');

        switch ($slug) {
            case 'retrieve':
                $cardHolder
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCardHolder($params));

                break;

            case 'create':
                $cardHolder
                    ->shouldReceive('create')
                    ->with([
                        'billing' => $params['billing'],
                        'name' => $params['name'],
                        'type' => $params['type'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCardHolder($params));

                break;

            case 'update':
                $cardHolder
                    ->shouldReceive('update')
                    ->with($params['id'], [
                        'billing' => $params['billing'],
                    ], $this->stripeConnectParam())
                    ->andReturn($this->getStripeCardHolder($params));

                break;
        }
        
        $this->successful = true;

        return $cardHolder;
    }

    public function getStripeCardHolder(array $params)
    {
        $faker = Factory::create();

        $holder = new StripeCardHolder(['id' => Arr::get($params, 'id', 'ich_'.Str::random(8))]);
        $holder->object = 'issuing.cardholder';
        $holder->email = Arr::get($params, 'email', $faker->unique()->safeEmail);
        $holder->type = Arr::get($params, 'type', 'individual');
        $holder->name = Arr::get($params, 'name', $faker->name);
        $holder->phone_number = Arr::get($params, 'phone_number');
        $holder->status = Arr::get($params, 'status', 'active');
        $holder->billing = (object) [
            'address' => (object) [
                'line1' => Arr::get($params, 'billing.address.line1', $faker->streetAddress),
                'line2' => Arr::get($params, 'billing.address.line2', $faker->secondaryAddress),
                'city' => Arr::get($params, 'billing.address.city', $faker->city),
                'state' => Arr::get($params, 'billing.address.state', $faker->stateAbbr),
                'postal_code' => Arr::get($params, 'billing.address.postal_code', $faker->postcode),
                'country' => Arr::get($params, 'billing.address.country', $faker->countryCode),
            ],
        ];
        $holder->metadata = (object) Arr::get($params, 'metadata', []);
        $holder->is_default = Arr::get($params, 'is_default', false);
        $holder->livemode = Arr::get($params, 'livemode', false);
        $holder->created = time();

        $this->successful = true;

        return $holder;
    }

    private function getStripeCard(array $params = [])
    {
        $card = new StripeCard(['id' => 'card_1234']);

        $faker = Factory::create();

        // general info
        $card->brand = $faker->creditCardType;
        $card->object = 'card';
        $card->country = 'US';
        $card->customer = 'cus_1';
        $card->name = null;
        $card->last4 = '4242';
        $card->exp_month = $faker->numberBetween(1, 12);
        $card->exp_year = now()->addYear()->year;

        // address
        $card->address_line1 = Arr::get($params, 'address_line1');
        $card->address_line2 = Arr::get($params, 'address_line2');
        $card->address_city = Arr::get($params, 'address_city');
        $card->address_state = Arr::get($params, 'address_state');
        $card->address_zip = Arr::get($params, 'address_zip');
        $card->address_country = Arr::get($params, 'address_country');
        $card->address_line1_check = Arr::get($params, 'address_line1_check');
        $card->address_zip_check = Arr::get($params, 'address_zip_check');

        return $card;
    }

    /***************************************************************************************
     ** BALANCE
     ***************************************************************************************/

    private function getMockStripeBalance(string $slug, $params = [])
    {
        $balance = m::mock('alias:StripeBalance');

        switch ($slug) {
            case 'retrieve':
                $balance
                    ->shouldReceive('retrieve')
                    ->andReturn($this->getStripeBalance());

                break;

            case 'retrieve-connect':
                $balance
                    ->shouldReceive('retrieve')
                    ->with($params)
                    ->andReturn($this->getStripeBalance());

                break;
        }

        $this->successful = true;

        return $balance;
    }

    private function getStripeBalance()
    {
        $balance = new StripeBalance();

        $balance->object = 'balance';
        $balance->available = [
            (object) [
                "amount" => 1500,
                "currency" => "usd",
                "source_types" => (object) [
                    "card" => 0,
                ],
            ],
        ];
        $balance->connect_reserved = [
            [
                "amount" => 0,
                "currency" => "usd",
            ],
        ];
        $balance->livemode = false;
        $balance->pending = [
            (object) [
                "amount" => 1000,
                "currency" => "usd",
                "source_types" => (object) [
                    "card" => 0,
                ],
            ],
        ];;

        return $balance;
    }
}
