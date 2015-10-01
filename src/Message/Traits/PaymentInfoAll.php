<?php

namespace Recca0120\AllPay\Message\Traits;

trait PaymentInfoAll
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

    public function setPaymentType($value)
    {
        return $this->setParameter('PaymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('PaymentType');
    }

    public function setTradeDate($value)
    {
        return $this->setParameter('TradeDate', $value);
    }

    public function getTradeDate()
    {
        return $this->getParameter('TradeDate');
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
