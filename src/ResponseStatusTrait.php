<?php

namespace R64\Stripe;

trait ResponseStatusTrait
{
    private $successful = false;

    private $message = null;

    private $errorType = null;

    private $statusCode = null;

    private $exception = null;

    public function attemptSuccessful()
    {
        return $this->successful;
    }

    public function getErrorMessage()
    {
        return $this->message;
    }

    public function getErrorType()
    {
        return $this->errorType;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getStripeException()
    {
        return $this->exception;
    }
}
