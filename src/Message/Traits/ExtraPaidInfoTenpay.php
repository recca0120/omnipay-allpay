<?php

namespace Recca0120\AllPay\Message\Traits;

trait ExtraPaidInfoTenpay
{
    public function setTenpayTradeNo($value)
    {
        return $this->setParameter('TenpayTradeNo', $value);
    }

    public function getTenpayTradeNo()
    {
        return $this->getParameter('TenpayTradeNo');
    }
}
