<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Recca0120\AllPay\Constants\PaymentMethod;
use Recca0120\AllPay\Helper;

class CompleteAuthorizeRequest extends AbstractRequest
{
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
                //$szResult = str_replace('/', '%2F', $szResult);
                //$szResult = str_replace('?', '%3F', $szResult);
                //$szResult = str_replace('%', '%25', $szResult);
                //$szResult = str_replace('#', '%23', $szResult);
                //$szResult = str_replace('&', '%26', $szResult);
                //$szResult = str_replace('=', '%3D', $szResult);
                parse_str($szResult, $data);
            } else {
                throw new InvalidRequestException(implode('- ', $arErrors));
            }
        }

        return $data;
    }

    public function sendData($data)
    {
        $this->response = new CompleteAuthorizeResponse($this, $data);

        return $this->response;
    }

    public function setMerchantTradeNo($value)
    {
        return $this->setParameter('merchantTradeNo', $value);
    }

    public function getMerchantTradeNo()
    {
        return $this->getParameter('merchantTradeNo');
    }

    public function setPaymentDate($value)
    {
        return $this->setParameter('paymentDate', $value);
    }

    public function getPaymentDate()
    {
        return $this->getParameter('paymentDate');
    }

    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    public function setPaymentTypeChargeFee($value)
    {
        return $this->setParameter('paymentTypeChargeFee', $value);
    }

    public function getPaymentTypeChargeFee()
    {
        return $this->getParameter('paymentTypeChargeFee');
    }

    public function setRtnCode($value)
    {
        return $this->setParameter('rtnCode', $value);
    }

    public function getRtnCode()
    {
        return $this->getParameter('rtnCode');
    }

    public function setRtnMsg($value)
    {
        return $this->setParameter('rtnMsg', $value);
    }

    public function getRtnMsg()
    {
        return $this->getParameter('rtnMsg');
    }

    public function setSimulatePaid($value)
    {
        return $this->setParameter('simulatePaid', $value);
    }

    public function getSimulatePaid()
    {
        return $this->getParameter('simulatePaid');
    }

    public function setTradeAmt($value)
    {
        return $this->setParameter('tradeAmt', $value);
    }

    public function getTradeAmt()
    {
        return $this->getParameter('tradeAmt');
    }

    public function setTradeDate($value)
    {
        return $this->setParameter('tradeDate', $value);
    }

    public function getTradeDate()
    {
        return $this->getParameter('tradeDate');
    }

    public function setTradeNo($value)
    {
        return $this->setParameter('tradeNo', $value);
    }

    public function getTradeNo()
    {
        return $this->getParameter('tradeNo');
    }

    public function setCheckMacValue($value)
    {
        return $this->setParameter('checkMacValue', $value);
    }

    public function getCheckMacValue()
    {
        return $this->getParameter('checkMacValue');
    }
}
