<?php

namespace Recca0120\AllPay\Message\Traits;

// AioChargeback
trait RefundAioChargeback
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

    public function setTradeNo($value)
    {
        return $this->setParameter('TradeNo', $value);
    }

    public function getTradeNo()
    {
        return $this->getParameter('TradeNo');
    }

    public function setChargeBackTotalAmount($value)
    {
        return $this->setParameter('ChargeBackTotalAmount', $value);
    }

    public function getChargeBackTotalAmount()
    {
        return $this->getParameter('ChargeBackTotalAmount');
    }

    public function setCheckMacValue($value)
    {
        return $this->setParameter('CheckMacValue', $value);
    }

    public function getCheckMacValue()
    {
        return $this->getParameter('CheckMacValue');
    }

    public function setRemark($value)
    {
        return $this->setParameter('Remark', $value);
    }

    public function getRemark()
    {
        return $this->getParameter('Remark');
    }

    public function setPlatformID($value)
    {
        return $this->setParameter('PlatformID', $value);
    }

    public function getPlatformID()
    {
        return $this->getParameter('PlatformID');
    }
}
