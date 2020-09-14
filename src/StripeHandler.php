<?php

namespace R64\Stripe;

use R64\Stripe\Objects\Card;
use R64\Stripe\Objects\Charge;
use R64\Stripe\Objects\Customer;
use R64\Stripe\Objects\Invoice;
use R64\Stripe\Objects\InvoiceItem;
use R64\Stripe\Objects\Plan;
use R64\Stripe\Objects\Product;
use R64\Stripe\Objects\Subscription;
use R64\Stripe\Objects\Token;
use Exception;
use Illuminate\Support\Arr;
use Stripe\Charge as StripeCharge;
use Stripe\Customer as StripeCustomer;
use Stripe\Invoice as StripeInvoice;
use Stripe\InvoiceItem as StripeInvoiceItem;
use Stripe\Issuing\Card as StripeCard;
use Stripe\Plan as StripePlan;
use Stripe\Product as StripeProduct;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Token as StripeToken;

class StripeHandler implements StripeInterface
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

    /***************************************************************************************
     ** CHARGES
     ***************************************************************************************/

    public function listCharges(array $params)
    {
        $result = $this->attemptRequest('list-charges', Arr::except($params, 'as_stripe_collection'));
        if (Arr::get($params, 'as_stripe_collection')) {
            return $result;
        }
        if ($result) {
            return collect($result->data);
        }

        return collect([]);
    }

    public function getCharge(string $id, bool $noWrapper = false)
    {
        $charge = $this->attemptRequest('get-charge', $id);
        if ($charge) {
            return $noWrapper ? $charge : new Charge($charge);
        }
    }

    public function createCharge(array $params)
    {
        $charge = $this->attemptRequest('create-charge', $params);
        if ($charge) {
            return new Charge($charge);
        }
    }

    public function createConnectCharge(array $params)
    {
        $charge = $this->attemptRequest('create-charge', $params);
        if ($charge) {
            return new Charge($charge);
        }
    }

    /***************************************************************************************
     ** CUSTOMERS
     ***************************************************************************************/

    public function listCustomers(array $params, bool $asStripeCollection = false)
    {
        $result = $this->attemptRequest('list-customers', Arr::except($params, 'as_stripe_collection'));
        if (Arr::get($params, 'as_stripe_collection')) {
            return $result;
        }
        if ($result) {
            return collect($result->data);
        }

        return collect([]);
    }

    public function getCustomer(string $id, bool $noWrapper = false)
    {
        $customer = $this->attemptRequest('get-customer', $id);
        if ($customer) {
            return $noWrapper ? $customer : new Customer($customer);
        }
    }

    public function createCustomer(array $params)
    {
        $customer = $this->attemptRequest('create-customer', $params);
        if ($customer) {
            return new Customer($customer);
        }
    }

    public function updateCustomer(array $params)
    {
        $customers = $this->listCustomers(['email' => $params['email']]);
        if (! $customers->count() == 1) {
            throw new Exception('Cannot update customer: '.$customers->count(), 400);
        }
        $customer = $customers->first();
        $updatedCustomer = $this->attemptRequest('update-customer', $customer->id, $params);
        if ($updatedCustomer) {
            return new Customer($updatedCustomer);
        }
    }

    public function deleteCustomer(string $id)
    {
        $customer = $this->getCustomer($id, true);
        if ($customer) {
            return $customer->delete();
        }
    }

    /***************************************************************************************
     ** SUBSCRIPTIONS
     ***************************************************************************************/

    public function createProduct(array $params)
    {
        $product = $this->attemptRequest('create-product', $params);
        if ($product) {
            return new Product($product);
        }
    }

    public function getProduct(string $id, bool $noWrapper = false)
    {
        $product = $this->attemptRequest('get-product', $id);
        if ($product) {
            return $noWrapper ? $product : new Product($product);
        }
    }

    public function deleteProduct(string $id)
    {
        $product = $this->getProduct($id, true);
        if (! $product) {
            throw new Exception('Product Not Found');
        }
        $product->delete();
    }

    public function createPlan(array $params)
    {
        $plan = $this->attemptRequest('create-plan', $params);
        if ($plan) {
            return new Plan($plan);
        }
    }

    public function getPlan(string $id, bool $noWrapper = false)
    {
        $plan = $this->attemptRequest('get-plan', $id);
        if ($plan) {
            return $noWrapper ? $plan : new Plan($plan);
        }
    }

    public function deletePlan(string $id)
    {
        $plan = $this->getPlan($id, true);
        if (! $plan) {
            throw new Exception('Plan Not Found');
        }
        $plan->delete();
    }

    public function createSubscription(array $params)
    {
        $subscription = $this->attemptRequest('create-subscription', $params);
        if ($subscription) {
            return new Subscription($subscription);
        }
    }

    public function createInvoice(array $params)
    {
        $invoice = $this->attemptRequest('create-invoice', $params);
        if ($invoice) {
            return new Invoice($invoice);
        }
    }

    public function createInvoiceItem(array $params)
    {
        $invoiceItem = $this->attemptRequest('create-invoice-item', $params);
        if ($invoiceItem) {
            return new InvoiceItem($invoiceItem);
        }
    }

    public function getInvoice(string $id, bool $noWrapper = false)
    {
        $invoice = $this->attemptRequest('get-invoice', $id);
        if ($invoice) {
            return $noWrapper ? $invoice : new Invoice($invoice);
        }
    }

    public function listSubscriptions(array $params, bool $noWrapper = false)
    {
        $result = $this->attemptRequest('list-subscriptions', Arr::except($params, 'as_stripe_collection'));
        if (Arr::get($params, 'as_stripe_collection')) {
            return $result;
        }
        if ($result) {
            return collect($result->data);
        }

        return collect([]);
    }

    public function getSubscription(string $id, bool $noWrapper = false)
    {
        $subscription = $this->attemptRequest('get-subscription', $id);
        if ($subscription) {
            return $noWrapper ? $subscription : new Subscription($subscription);
        }
    }

    /***************************************************************************************
     ** CARDS
     ***************************************************************************************/

    public function getCard(string $customerId, string $cardId, bool $noWrapper = false)
    {
        $customer = $this->getCustomer($customerId, true);
        $card = $customer->sources->retrieve($cardId);
        if ($card) {
            return $noWrapper ? $card : new Card($card);
        }
    }

    public function createCard(array $params, bool $noWrapper = false)
    {
        $card = $this->attemptRequest('create-card', $params);
        if ($card) {
            return $noWrapper ? $card : new Card($card);
        }
    }

    public function updateCard(string $customerId, string $cardId, array $params, bool $noWrapper = false)
    {
        $customer = $this->getCustomer($customerId, true);
        $card = $customer->sources->retrieve($cardId);
        foreach ($params as $property => $value) {
            $card->{$property} = $value;
        }
        $card->save();
        if ($card) {
            return $noWrapper ? $card : new Card($card);
        }
    }

    /*****************************************************************************************
     ** TOKEN
     *****************************************************************************************/

    public function getToken(string $id, bool $noWrapper = false)
    {
        $token = $this->attemptRequest('get-token', $id);
        if ($token) {
            return $noWrapper ? $token : new Token($token);
        }
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function call(string $slug, ...$params)
    {
        switch ($slug) {
            case 'list-charges':
                return StripeCharge::all($params[0], $this->stripeConnectParam());

                break;
            case 'create-charge':
                return StripeCharge::create($params[0], $this->stripeConnectParam());

                break;
            case 'list-customers':
                return StripeCustomer::all($params[0], $this->stripeConnectParam());
            case 'get-customer':
                return StripeCustomer::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'create-customer':
                return StripeCustomer::create($params[0], $this->stripeConnectParam());

                break;
            case 'update-customer':
                return StripeCustomer::update($params[0], $params[1], $this->stripeConnectParam());

                break;
            case 'create-product':
                return StripeProduct::create($params[0], $this->stripeConnectParam());

                break;
            case 'get-product':
                return StripeProduct::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'create-plan':
                return StripePlan::create($params[0], $this->stripeConnectParam());

                break;
            case 'get-plan':
                return StripePlan::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'list-subscriptions':
                return StripeSubscription::all($params[0], $this->stripeConnectParam());

                break;
            case 'create-subscription':
                return StripeSubscription::create($params[0], $this->stripeConnectParam());

                break;
            case 'create-invoice':
                return StripeInvoice::create($params[0], $this->stripeConnectParam());

                break;
            case 'create-invoice-item':
                return StripeInvoiceItem::create($params[0], $this->stripeConnectParam());

                break;
            case 'get-charge':
                return StripeCharge::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'get-invoice':
                return StripeInvoice::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'get-subscription':
                return StripeSubscription::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'get-token':
                return StripeToken::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'get-card':
                return StripeCard::retrieve($params[0], $this->stripeConnectParam());

                break;
            case 'create-card':
                return $this->saveCard($params[0]['customer'], $params[0]['token']);

                break;
            case 'update-card':
                return StripeCard::update($params[0], $params[1], $this->stripeConnectParam());

                break;
        }
    }

    public function saveCard($customerId, $token)
    {
        $customer = StripeCustomer::retrieve($customerId, $this->stripeConnectParam());

        return $customer->sources->create(['source' => $token]);
    }

    public function attemptRequest(string $slug, ...$params)
    {
        try {
            $data = $this->call($slug, ...$params);
            $this->successful = true;

            return $data;
        } catch (\Stripe\Exception\CardException $e) {
            $this->recordError($e, 'Card Error', 'card_error');
        } catch (\Stripe\Exception\RateLimitException $e) {
            $this->recordError($e, 'Rate limit exceeded', 'rate_limit_error');
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $this->recordError($e, 'Invalid request data', 'invalid_request_error');
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $this->recordError($e, 'Authentication error', 'authentication_error');
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $this->recordError($e, 'API connection error', 'api_connection_error');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->recordError($e, 'General Stripe error', 'general_stripe_error');
        } catch (Exception $e) {
            $this->successful = false;
            $this->message = $e->getMessage();
            $this->errorType = 'unkown_exception';
            $this->statusCode = $e->getCode();
            $this->exception = $e;
        }
    }

    public function recordError(Exception $e, string $altMessage = null, string $altType = null)
    {
        $body = $e->getJsonBody();
        $err = $body['error'];

        $this->successful = false;
        $this->message = Arr::get($err, 'message', $altMessage);
        $this->errorType = Arr::get($err, 'type', $altType);
        $this->statusCode = $e->getHttpStatus();
        $this->exception = $e;
    }

    public function stripeConnectParam()
    {
        if ($this->skipConnect) {
            return;
        }

        return ['stripe_account' => $this->stripeConnectId];
    }
}
