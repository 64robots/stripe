<?php

namespace R64\Stripe;

use R64\Stripe\MockHandler;
use R64\Stripe\StripeHandler;

class PaymentProcessor
{
    use ResponseStatusTrait;

    /**
     * @var Stripe
     */
    private $handler;

    public function __construct(string $handlerSlug = 'default', $options = [])
    {
        $this->setHandler($handlerSlug, $options);
    }

    /***************************************************************************************
     ** CHARGES
     ***************************************************************************************/

    public function createCharge(array $data)
    {
        $charge = $this->handler->createCharge($data);
        $this->recordAttempt();

        return $charge;
    }

    /***************************************************************************************
     ** CUSTOMERS
     ***************************************************************************************/

    public function createCustomer(array $data)
    {
        $customer = $this->handler->createCustomer($data);
        $this->recordAttempt();

        return $customer;
    }

    public function updateCustomer(array $data)
    {
        $customer = $this->handler->updateCustomer($data);
        $this->recordAttempt();

        return $customer;
    }

    public function getCustomer($id)
    {
        $customer = $this->handler->getCustomer($id);
        $this->recordAttempt();

        return $customer;
    }

    public function listCustomers(array $data)
    {
        $customers = $this->handler->listCustomers($data);
        $this->recordAttempt();

        return $customers;
    }

    public function deleteCustomer($id)
    {
        $this->handler->deleteCustomer($id);
        $this->recordAttempt();
    }

    /***************************************************************************************
     ** SUBSCRIPTIONS
     ***************************************************************************************/

    public function createProduct(array $data)
    {
        $product = $this->handler->createProduct($data);
        $this->recordAttempt();

        return $product;
    }

    public function createPlan(array $data)
    {
        $plan = $this->handler->createPlan($data);
        $this->recordAttempt();

        return $plan;
    }

    public function createSubscription(array $data)
    {
        $subscription = $this->handler->createSubscription($data);
        $this->recordAttempt();

        return $subscription;
    }

    public function createInvoice(array $data)
    {
        $invoice = $this->handler->createInvoice($data);
        $this->recordAttempt();

        return $invoice;
    }

    public function createInvoiceItem(array $data)
    {
        $invoiceItem = $this->handler->createInvoiceItem($data);
        $this->recordAttempt();

        return $invoiceItem;
    }

    public function getInvoice($id)
    {
        $invoice = $this->handler->getInvoice($id);
        $this->recordAttempt();

        return $invoice;
    }

    public function getSubscription($id)
    {
        $subscription = $this->handler->getSubscription($id);
        $this->recordAttempt();

        return $subscription;
    }

    /***************************************************************************************
     ** CARD
     ***************************************************************************************/

    public function getCard($customerId, $cardId)
    {
        $card = $this->handler->getCard($customerId, $cardId);
        $this->recordAttempt();

        return $card;
    }

    public function createCard(array $data)
    {
        $card = $this->handler->createCard($data);
        $this->recordAttempt();

        return $card;
    }

    public function updateCard($customerId, $cardId, array $data)
    {
        $card = $this->handler->updateCard($customerId, $cardId, $data);
        $this->recordAttempt();

        return $card;
    }

    /***************************************************************************************
     ** CARD HOLDER
     ***************************************************************************************/

    public function getCardHolder($id)
    {
        $cardHolder = $this->handler->getCardHolder($id);
        $this->recordAttempt();

        return $cardHolder;
    }

    public function createCardHolder(array $data)
    {
        $cardHolder = $this->handler->createCardHolder($data);
        $this->recordAttempt();

        return $cardHolder;
    }

    public function updateCardHolder($id, array $data)
    {
        $cardHolder = $this->handler->updateCardHolder($id, $data);
        $this->recordAttemp();

        return $cardHolder;
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function setHandler(string $slug, $options)
    {
        switch ($slug) {
            case 'stripe':
                $this->handler = new StripeHandler($options);

                break;
            case 'mock':
                $this->handler = new MockHandler($options);

                break;
            case 'default':
                $this->handler = $this->getDefaultHandler($options);

                break;
        }
    }

    public function getDefaultHandler($options = [])
    {
        if (config('stripe.mock')) {
            return new MockHandler($options);
        }

        return new StripeHandler($options);
    }

    public function recordAttempt()
    {
        $this->successful = $this->handler->attemptSuccessful();
        $this->message = $this->handler->getErrorMessage();
        $this->errorType = $this->handler->getErrorType();
    }
}
