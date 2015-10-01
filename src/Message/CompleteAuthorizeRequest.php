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

    public function setBankCode($value)
    {
        return $this->setParameter('BankCode', $value);
    }

    public function getBankCode()
    {
        return $this->getParameter('BankCode');
    }

    public function setvAccount($value)
    {
        return $this->setParameter('vAccount', $value);
    }

    public function getvAccount()
    {
        return $this->getParameter('vAccount');
    }

    public function setExpireDate($value)
    {
        return $this->setParameter('ExpireDate', $value);
    }

    public function getExpireDate()
    {
        return $this->getParameter('ExpireDate');
    }

    public function setPaymentNo($value)
    {
        return $this->setParameter('PaymentNo', $value);
    }

    public function getPaymentNo()
    {
        return $this->getParameter('PaymentNo');
    }

    public function setBarcode1($value)
    {
        return $this->setParameter('Barcode1', $value);
    }

    public function getBarcode1()
    {
        return $this->getParameter('Barcode1');
    }

    public function setBarcode2($value)
    {
        return $this->setParameter('Barcode2', $value);
    }

    public function getBarcode2()
    {
        return $this->getParameter('Barcode2');
    }

    public function setBarcode3($value)
    {
        return $this->setParameter('Barcode3', $value);
    }

    public function getBarcode3()
    {
        return $this->getParameter('Barcode3');
    }

    public function setRtnCode($value)
    {
        return $this->setParameter('RtnCode', $value);
    }

    public function getRtnCode()
    {
        return $this->getParameter('RtnCode');
    }

    public function setRtnMsg($value)
    {
        return $this->setParameter('RtnMsg', $value);
    }

    public function getRtnMsg()
    {
        return $this->getParameter('RtnMsg');
    }

    public function setTradeNo($value)
    {
        return $this->setParameter('TradeNo', $value);
    }

    public function getTradeNo()
    {
        return $this->getParameter('TradeNo');
    }

    public function setTradeAmt($value)
    {
        return $this->setParameter('TradeAmt', $value);
    }

    public function getTradeAmt()
    {
        return $this->getParameter('TradeAmt');
    }

    public function setPaymentDate($value)
    {
        return $this->setParameter('PaymentDate', $value);
    }

    public function getPaymentDate()
    {
        return $this->getParameter('PaymentDate');
    }

    public function setPaymentType($value)
    {
        return $this->setParameter('PaymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('PaymentType');
    }

    public function setPaymentTypeChargeFee($value)
    {
        return $this->setParameter('PaymentTypeChargeFee', $value);
    }

    public function getPaymentTypeChargeFee()
    {
        return $this->getParameter('PaymentTypeChargeFee');
    }

    public function setTradeDate($value)
    {
        return $this->setParameter('TradeDate', $value);
    }

    public function getTradeDate()
    {
        return $this->getParameter('TradeDate');
    }

    public function setSimulatePaid($value)
    {
        return $this->setParameter('SimulatePaid', $value);
    }

    public function getSimulatePaid()
    {
        return $this->getParameter('SimulatePaid');
    }

    public function setCheckMacValue($value)
    {
        return $this->setParameter('CheckMacValue', $value);
    }

    public function getCheckMacValue()
    {
        return $this->getParameter('CheckMacValue');
    }

    public function setPeriodType($value)
    {
        return $this->setParameter('PeriodType', $value);
    }

    public function getPeriodType()
    {
        return $this->getParameter('PeriodType');
    }

    public function setFrequency($value)
    {
        return $this->setParameter('Frequency', $value);
    }

    public function getFrequency()
    {
        return $this->getParameter('Frequency');
    }

    public function setExecTimes($value)
    {
        return $this->setParameter('ExecTimes', $value);
    }

    public function getExecTimes()
    {
        return $this->getParameter('ExecTimes');
    }

    public function setgwsr($value)
    {
        return $this->setParameter('gwsr', $value);
    }

    public function getgwsr()
    {
        return $this->getParameter('gwsr');
    }

    public function setProcessDate($value)
    {
        return $this->setParameter('ProcessDate', $value);
    }

    public function getProcessDate()
    {
        return $this->getParameter('ProcessDate');
    }

    public function setAuthCode($value)
    {
        return $this->setParameter('AuthCode', $value);
    }

    public function getAuthCode()
    {
        return $this->getParameter('AuthCode');
    }

    public function setFirstAuthAmount($value)
    {
        return $this->setParameter('FirstAuthAmount', $value);
    }

    public function getFirstAuthAmount()
    {
        return $this->getParameter('FirstAuthAmount');
    }

    public function setTimeStamp($value)
    {
        return $this->setParameter('TimeStamp', $value);
    }

    public function getTimeStamp()
    {
        return $this->getParameter('TimeStamp');
    }

    public function setPlatformID($value)
    {
        return $this->setParameter('PlatformID', $value);
    }

    public function getPlatformID()
    {
        return $this->getParameter('PlatformID');
    }

    public function setWebATMAccBank($value)
    {
        return $this->setParameter('WebATMAccBank', $value);
    }

    public function getWebATMAccBank()
    {
        return $this->getParameter('WebATMAccBank');
    }

    public function setWebATMAccNo($value)
    {
        return $this->setParameter('WebATMAccNo', $value);
    }

    public function getWebATMAccNo()
    {
        return $this->getParameter('WebATMAccNo');
    }

    public function setWebATMBankName($value)
    {
        return $this->setParameter('WebATMBankName', $value);
    }

    public function getWebATMBankName()
    {
        return $this->getParameter('WebATMBankName');
    }

    public function setATMAccBank($value)
    {
        return $this->setParameter('ATMAccBank', $value);
    }

    public function getATMAccBank()
    {
        return $this->getParameter('ATMAccBank');
    }

    public function setATMAccNo($value)
    {
        return $this->setParameter('ATMAccNo', $value);
    }

    public function getATMAccNo()
    {
        return $this->getParameter('ATMAccNo');
    }

    public function setPayFrom($value)
    {
        return $this->setParameter('PayFrom', $value);
    }

    public function getPayFrom()
    {
        return $this->getParameter('PayFrom');
    }

    public function setAlipayID($value)
    {
        return $this->setParameter('AlipayID', $value);
    }

    public function getAlipayID()
    {
        return $this->getParameter('AlipayID');
    }

    public function setAlipayTradeNo($value)
    {
        return $this->setParameter('AlipayTradeNo', $value);
    }

    public function getAlipayTradeNo()
    {
        return $this->getParameter('AlipayTradeNo');
    }

    public function setTenpayTradeNo($value)
    {
        return $this->setParameter('TenpayTradeNo', $value);
    }

    public function getTenpayTradeNo()
    {
        return $this->getParameter('TenpayTradeNo');
    }

    public function setProcess_date($value)
    {
        return $this->setParameter('process_date', $value);
    }

    public function getProcess_date()
    {
        return $this->getParameter('process_date');
    }

    public function setAuth_code($value)
    {
        return $this->setParameter('auth_code', $value);
    }

    public function getAuth_code()
    {
        return $this->getParameter('auth_code');
    }

    public function setStage($value)
    {
        return $this->setParameter('stage', $value);
    }

    public function getStage()
    {
        return $this->getParameter('stage');
    }

    public function setStast($value)
    {
        return $this->setParameter('stast', $value);
    }

    public function getStast()
    {
        return $this->getParameter('stast');
    }

    public function setStaed($value)
    {
        return $this->setParameter('staed', $value);
    }

    public function getStaed()
    {
        return $this->getParameter('staed');
    }

    public function setEci($value)
    {
        return $this->setParameter('eci', $value);
    }

    public function getEci()
    {
        return $this->getParameter('eci');
    }

    public function setCard4no($value)
    {
        return $this->setParameter('card4no', $value);
    }

    public function getCard4no()
    {
        return $this->getParameter('card4no');
    }

    public function setCard6no($value)
    {
        return $this->setParameter('card6no', $value);
    }

    public function getCard6no()
    {
        return $this->getParameter('card6no');
    }

    public function setRed_dan($value)
    {
        return $this->setParameter('red_dan', $value);
    }

    public function getRed_dan()
    {
        return $this->getParameter('red_dan');
    }

    public function setRed_de_amt($value)
    {
        return $this->setParameter('red_de_amt', $value);
    }

    public function getRed_de_amt()
    {
        return $this->getParameter('red_de_amt');
    }

    public function setrEd_ok_amt($value)
    {
        return $this->setParameter('red_ok_amt', $value);
    }

    public function getrEd_ok_amt()
    {
        return $this->getParameter('red_ok_amt');
    }

    public function setRed_yet($value)
    {
        return $this->setParameter('red_yet', $value);
    }

    public function getRed_yet()
    {
        return $this->getParameter('red_yet');
    }

    public function setPeriodAmount($value)
    {
        return $this->setParameter('PeriodAmount', $value);
    }

    public function getPeriodAmount()
    {
        return $this->getParameter('PeriodAmount');
    }

    public function setTotalSuccessTimes($value)
    {
        return $this->setParameter('TotalSuccessTimes', $value);
    }

    public function getTotalSuccessTimes()
    {
        return $this->getParameter('TotalSuccessTimes');
    }

    public function setTotalSuccessAmount($value)
    {
        return $this->setParameter('TotalSuccessAmount', $value);
    }

    public function getTotalSuccessAmount()
    {
        return $this->getParameter('TotalSuccessAmount');
    }

    public function setExecStatus($value)
    {
        return $this->setParameter('ExecStatus', $value);
    }

    public function getExecStatus()
    {
        return $this->getParameter('ExecStatus');
    }

    public function setExecLog($value)
    {
        return $this->setParameter('ExecLog', $value);
    }

    public function getExecLog()
    {
        return $this->getParameter('ExecLog');
    }
}
