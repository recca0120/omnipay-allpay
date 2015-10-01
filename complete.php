<?php

require __DIR__.'/vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('\\Recca0120\\AllPay\\Gateway');
$gateway->initialize([
    'testMode' => true,
]);

echo '<pre>';

$response = $gateway->completePurchase($_POST)->send();

// Process response
if ($response->isSuccessful()) {
    // Payment was successful
    print_r($response->getData());
} elseif ($response->isRedirect()) {
    // Redirect to offsite payment gateway
    // dump($response);
    $response->redirect();
} else {
    // Payment failed
    echo $response->getMessage();
}
