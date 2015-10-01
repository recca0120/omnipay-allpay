<?php

namespace Recca0120\AllPay\Message\Traits;

// ReturnURL
trait PaymentPeriodReturnURL
{
    public function setMerchantID($value)
    {
        return $this->setParameter('MerchantID', $value);
    }

    public function getMerchantID()
    {
        return $this->getParameter('MerchantID');
    }

    public function setMerchantTradeNo($value)
    {
        return $this->setParameter('MerchantTradeNo', $value);
    }

    public function getMerchantTradeNo()
    {
        return $this->getParameter('MerchantTradeNo');
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

    public function setAmount($value)
    {
        return $this->setParameter('Amount', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('Amount');
    }

    public function setGwsr($value)
    {
        return $this->setParameter('gwsr', $value);
    }

    public function getGwsr()
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

    public function setTotalSuccessTimes($value)
    {
        return $this->setParameter('TotalSuccessTimes', $value);
    }

    public function getTotalSuccessTimes()
    {
        return $this->getParameter('TotalSuccessTimes');
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
}
