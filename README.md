# Omnipay: PayU

**PayU Romania driver for the Omnipay payment processing library**

[![Latest Stable Version](https://poser.pugx.org/keops007/omnipay-payu-romania/version)](https://packagist.org/packages/keops007/omnipay-payu-romania)
[![Total Downloads](https://poser.pugx.org/keops007/omnipay-payu-romania/d/total.png)](https://packagist.org/packages/keops007/omnipay-payu-romania)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.6+. This package implements PayU support for Omnipay.

## Installation

```
$ composer require keops007/omnipay-payu-romania
```

## Basic Usage

1. Use Omnipay gateway class:

```php
    use Omnipay\Omnipay;
```

2. Initialize PayU gateway:

```php
    $gateway = Omnipay::create('PayU');
    $gateway->setMerchantName(env('MERCHANT_NAME'));
    $gateway->setSecretKey(env('SECRET_KEY'));
```

3. Call purchase, it will automatically redirect to PayU hosted page

```php
    $purchase = $gateway->purchase([
            'amount' => 100,
            'transactionId' => 1,
            'orderDate' => date('Y-m-d H:i:s'),
            'items' => [
                new \Omnipay\PayU\Item([
                    'name' => 'Item',
                    'code' => 'ItemCode',
                    'price' => '100',
                    'priceType' => 'NET',
                    'quantity' => 1,
                    'vat' => 0,
                ]),
            ]
        ])->send();
    
    $purchase->redirect();
```

4. Create a webhook controller to handle the callback request at your `RESULT_URL` and catch the webhook as follows

```php
    $gateway = Omnipay::create('PayU');
    $gateway->setMerchantName(env('MERCHANT_NAME'));
    $gateway->setSecretKey(env('SECRET_KEY'));
    
    $purchase = $gateway->completePurchase()->send();
    
    if ($purchase->isSuccessful()) {
        // Your logic
        
        return $purchase->completeResponse();
    }
```

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.
