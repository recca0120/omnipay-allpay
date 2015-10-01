<?php

namespace Recca0120\AllPay\Message\Traits;

trait ChoosePaymentTenpay
{
    public function setExpireTime($value)
    {
        return $this->setParameter('ExpireTime', $value);
    }

    public function getExpireTime()
    {
        return $this->getParameter('ExpireTime');
    }
}
