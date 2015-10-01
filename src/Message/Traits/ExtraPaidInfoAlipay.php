<?php

namespace Recca0120\AllPay\Message\Traits;

trait ExtraPaidInfoAlipay
{
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
}
