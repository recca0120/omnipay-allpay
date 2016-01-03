<?php

require __DIR__.'/vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('\\Recca0120\\AllPay\\Gateway');
$gateway->initialize([
    'testMode' => true,
]);

$response = $gateway->purchase([
    // 'transactionId' => 'StageTest'.time(),
    'transactionReference' => 'StageTest'.time(),
    'merchantTradeDate'    => date('Y/m/d H:i:s'),
    'returnUrl'            => 'http://221.169.233.107/laravel/public/receive',
    'clientBackUrl'        => 'http://localhost/omnipay-allpay/receive.php',
    'orderResultUrl'       => 'http://localhost/omnipay-allpay/complete.php',
    'clientRedirectURL'    => 'http://localhost/omnipay-allpay/complete.php',
    'creditInstallment'    => 3,
    'NeedExtraPaidInfo'    => 'N',
    'items'                => [[
        'name'     => '交易測試(測試)',
        'quantity' => 10,
        'price'    => 10,
    ]],
    'description' => '交易測試(測試)',
    'amount'      => 100,
])->send();

// Process response
if ($response->isSuccessful()) {
    // Payment was successful
    print_r($response);
} elseif ($response->isRedirect()) {
    // Redirect to offsite payment gateway
    // dump($response);
    $response->redirect();
} else {
    // Payment failed
    echo $response->getMessage();
}
