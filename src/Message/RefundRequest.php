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
        $response = (string) $response->getBody();
        // 檢查結果資料。
        if ($response == '1|OK') {
            $data['RtnCode'] = '1';
            $data['RtnMsg'] = 'OK';
        } else {
            $response = explode('-', str_replace('0|', '', $response));
            $data['RtnCode'] = $response[0];
            $data['RtnMsg'] = $response[1];
        }

        return $data;
    }
}
