<?php

namespace R64\Stripe\Processor;

use R64\Stripe\StripeInterface;
use R64\Stripe\ResponseStatusTrait;

class StripeProcessor
{
    use Card;
    use Plan;
    use Charge;
    use Invoice;
    use Product;
    use Customer;
    use CardHolder;
    use InvoiceItem;
    use Subscription;
    use ResponseStatusTrait;

    /**
     * @var Stripe
     */
    private $handler;

    public function __construct(StripeInterface $handler)
    {
        $this->handler = $handler;
    }

    public function recordAttempt()
    {
        $this->successful = $this->handler->attemptSuccessful();
        $this->message = $this->handler->getErrorMessage();
        $this->errorType = $this->handler->getErrorType();
        $this->statusCode = $this->handler->getStatusCode();
        $this->exception = $this->handler->getStripeException();
    }
}
