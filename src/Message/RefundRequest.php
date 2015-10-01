<?php

namespace Recca0120\AllPay\Message;

use Recca0120\AllPay\Helper;
use Recca0120\AllPay\Message\Traits\RefundAioChargeback;

class RefundRequest extends AbstractRequest
{
    use RefundAioChargeback;
    public $testEndPoint = 'http://payment-stage.allpay.com.tw/Cashier/AioChargeback';
    public $liveEndPoint = 'https://payment.allpay.com.tw/Cashier/AioChargeback';

    public function getData()
    {
        $data = Helper::skipParameters($this->getParameters());
        $data['CheckMacValue'] = $this->generateSignature($data);
        $response = $this->httpClient->post($this->getEndPoint(), null, $data)->send();
        $szResult = (string) $response->getBody();
        dump($szResult);
        dump($data);
        exit;
    }
}
