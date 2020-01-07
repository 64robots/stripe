Add stripe integration to laravel applications
==============================================

This package makes it easier to add stripe integrations to laravel applications

## Installation

To get the latest version, simply require the package using [Composer](https://getcomposer.org):

```bash
$ composer require R64/stripe
```

Once installed, if you are not using automatic package discovery, then you need to register the `R64\Stripe\StripeServiceProvider` service provider in your `config/app.php`.

## Usage
The class you'd propably interact with the most is `PaymentProcessor` class. The processor class can be injected into other classes or can be resolved from the container.

```
use R64\Stripe\PaymentProcessor;
.
.
.

$processor = app(PaymentProcessor::class);

$charge = $processor->createCharge([
    'customer' => 'cus_GVIiC06JWCq1mo',
    'amount' => 100,
    'currency' => 'USD',
    'source' => 'tok_visa'
]);
```

### Charges
- To charge a credit or a debit card, you create a Charge object. You can create a charge using the `createCharge` method on the `PaymentProcessor` class. A `R64\Stripe\Objects\Charge` object would be returned.

```
$charge = $processor->createCharge([
    'customer' => 'cus_GVIiC06JWCq1mo',
    'amount' => 100,
    'currency' => 'USD',
    'source' => 'tok_visa'
]);
```

### Customers
The processor allows you to create, update, list customers and get a single customer.

- To create a customer, use the `createCustomer` method on the processor. A `R64\Stripe\Objects\Customer` object would be returned.

```
$customer = $processor->createCustomer([
    'description' => 'Customer for jenny.rosen@example.com',
    'source' => 'tok_visa',
    'email' => jenny.rosen@example.com,
    'metadata' => [
        'first_name' => Rosen,
        'last_name' => Gina,
    ]
]);
```

- To update a customer, use the `updateCustomer` method on the processor. A `R64\Stripe\Objects\Customer` object would be returned.

```
$customer = $processor->updateCustomer([
    'id' => 1,
    'email' => jenny.rosen@example.com,
    'description' => Update jenny.rosen@example.com details,
    'source' => 'tok_visa'
]);
```

- To get a single customer, use the `getCustomer` method on the processor. A `R64\Stripe\Objects\Customer` object would be returned.

```
$customer = $processor->getCustomer('cus_GVIiC06JWCq1mo');
```

### Card
You can get a card, create and update a card using the processor.

- To get a single card details, use the `getCard` method on the processor. A `R64\Stripe\Objects\Card` object would be returned.

```
$card = $processor->getCard('cus_GVIiC06JWCq1mo', 'card_1FyI6w2eZvKYlo2COseWzZAo');
```

- To create a card, use the `createCard` method on the processor. A `R64\Stripe\Objects\Card` object would be returned.

```
$card = $processor->createCard([
    'source' => 'tok_visa'
]);
```

- To update a card, use the `updateCard` method on the processor. A `R64\Stripe\Objects\Card` object would be returned.

```
$card = $processor->updateCard(
    'cus_GVIiC06JWCq1mo',
    'card_1FyI6w2eZvKYlo2COseWzZAo',
    [
        'name' => 'Jenny Rosen'
    ]
);
```

### Plan and Subscription
Plans define the base price, currency, and billing cycle for subscriptions.

- To create a product, use the `createProduct` method on the processor. A `R64\Stripe\Objects\Product` object would be returned.

```
$product = $processor->createProduct([
    'name' => 'Monthly membership base fee',
    'type' => 'service',
]);
```

- To create a plan, use the `createPlan` method on the processor. A `R64\Stripe\Objects\Plan` object would be returned.

```
$plan = $processor->createPlan([
    'product' => ['name' => 'Gold special'],
    'nickname' => 'special,
    'interval' => 'month',
    'billing_scheme' => 'per_unit',
    'amount' => 100,
    'currency' => 'usd'
]);
```

- To create a subscription, use the `createSubscription` method on the processor. A `R64\Stripe\Objects\Subscription` object would be returned.

```
$subscription = $processor->createSubscription([
    'customer' => 'cus_GVIiC06JWCq1mo',
    'items' => [
        [
            'object' => 'list',
            'plan' => plan_GVIh1z2696UJyR
        ]
    ]
]);
```

- To create an invoice, use the `createInvoice` method on the processor. A `R64\Stripe\Objects\Invoice` object would be returned.

```
$invoice = $processor->createInvoice([
    'customer' => 'cus_GVIiC06JWCq1mo',
    'subscription' => 'sub_DUVhBH3LKxekhs',
]);
```

- To create an invoice item, use the `createInvoiceItem` method on the processor. A `R64\Stripe\Objects\InvoiceItem` object would be returned.

```
$invoiceItem = $processor->createInvoiceItem([
    'customer' => 'cus_GVIiC06JWCq1mo',
    'subscription' => 'sub_DUVhBH3LKxekhs',
    'amount' => 100,
    'currency' => 'usd',
]);
```

- To get an invoice details, use the `getInvoice` method. A `R64\Stripe\Objects\Invoice` object would be returned.

```
$invoice = $this->processor->getInvoice('in_1FJSdj2eZvKYlo2CCyOhyNxj');
```

- To get a subscription details, use the `getSubscription` method. A `R64\Stripe\Objects\Subscription` object would be returned.

```
$subscription = $this->processor->getSubscription(sub_DUVhBH3LKxekhs);
```

### Card Holder
You can create, update and get a card holder's details.

- To create a card holder, use the `createCardHolder` method. A `R64\Stripe\Objects\CardHolder` object would be returned.

```
$cardHolder = $processor->createCardHolder([
    'type' => 'individual',
    'name' => 'Jenny Rosen',
    'email' => 'jenny.rosen@example.com',
    'phone_number' => '+18888675309',
    'billing' => [
        'name' => 'Jenny Rosen',
        'address' => [
        'line1' => '1234 Main Street',
        'city' => 'San Francisco',
        'state' => 'CA',
        'country' => 'US',
        'postal_code' => '94111',
        ],
    ],
]);
```

- To update a card holders details, use the `updateCardHolder` method. A `R64\Stripe\Objects\CardHolder` object would be returned.

```
$cardHolder = $processor->updateCardHolder([
    'ich_1Ccy6F2eZvKYlo2ClnIm9bs4',
    [
        'metadata' => [
            'order_id' => '6735'
        ]
    ]
])
```

- To retrieve a card holder, use the `getCardHolder` method. A `R64\Stripe\Objects\CardHolder` object would be returned.

```
$cardHolder = $processor->getCardHolder('ich_1Ccy6F2eZvKYlo2ClnIm9bs4');
```
