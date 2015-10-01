<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Recca0120\AllPay\Constants\PaymentMethod;
use Recca0120\AllPay\Helper;
use Recca0120\AllPay\Message\Traits\PaymentInfoATM;
use Recca0120\AllPay\Message\Traits\PaymentInfoBarcode;
use Recca0120\AllPay\Message\Traits\PaymentReturnURL;

class CompleteAuthorizeRequest extends AbstractRequest
{
    use PaymentReturnURL,
    PaymentInfoATM,
    PaymentInfoBarcode {
        PaymentInfoATM::setExpireDate insteadof PaymentInfoBarcode;
        PaymentInfoATM::getExpireDate insteadof PaymentInfoBarcode;
    }

    public $testEndPoint = [
        PaymentMethod::ALL => 'http://payment-stage.allpay.com.tw/Cashier/QueryTradeInfo',
        PaymentMethod::CREDIT => 'https://payment-stage.allpay.com.tw/Cashier/QueryCreditCardPeriodInfo',
    ];
    public $liveEndPoint = [
        PaymentMethod::ALL => 'https://payment.allpay.com.tw/Cashier/QueryTradeInfo',
        PaymentMethod::CREDIT => 'https://payment.allpay.com.tw/Cashier/QueryCreditCardPeriodInfo',
    ];

    public function getEndPoint()
    {
        $paymentMethod = PaymentMethod::ALL;

        return $this->getTestMode() ? $this->testEndPoint[$paymentMethod] : $this->liveEndPoint[$paymentMethod];
    }

    public function getData()
    {
        $data = Helper::aliases($this->getParameters());

        if (isset($data['RtnCode']) === false) {
            $arErrors = [];
            $data['TimeStamp'] = time();

            // 呼叫查詢。
            if (strlen($this->getHashKey()) == 0) {
                array_push($arErrors, 'HashKey is required.');
            }
            if (strlen($this->getHashIV()) == 0) {
                array_push($arErrors, 'HashIV is required.');
            }
            if (strlen($data['MerchantID']) == 0) {
                array_push($arErrors, 'MerchantID is required.');
            }
            if (strlen($data['MerchantID']) > 10) {
                array_push($arErrors, 'MerchantID max langth as 10.');
            }
            if (strlen($data['MerchantTradeNo']) == 0) {
                array_push($arErrors, 'MerchantTradeNo is required.');
            }
            if (strlen($data['MerchantTradeNo']) > 20) {
                array_push($arErrors, 'MerchantTradeNo max langth as 20.');
            }
            if (strlen($data['TimeStamp']) == 0) {
                array_push($arErrors, 'TimeStamp is required.');
            }
            if (sizeof($arErrors) == 0) {
                $data['CheckMacValue'] = $this->generateSignature($data);
                $response = $this->httpClient->post($this->getEndPoint(), null, $data)->send();
                $szResult = (string) $response->getBody();
                $szResult = str_replace(' ', '%20', $szResult);
                $szResult = str_replace('+', '%2B', $szResult);
                // $szResult = str_replace('/', '%2F', $szResult);
                // $szResult = str_replace('?', '%3F', $szResult);
                // $szResult = str_replace('%', '%25', $szResult);
                // $szResult = str_replace('#', '%23', $szResult);
                // $szResult = str_replace('&', '%26', $szResult);
                // $szResult = str_replace('=', '%3D', $szResult);
                parse_str($szResult, $data);
            } else {
                throw new InvalidRequestException(implode('- ', $arErrors));
            }
        }

        return $data;
    }

    public function sendData($data)
    {
        $this->response = new Response($this, $data);

        return $this->response;
    }
}
