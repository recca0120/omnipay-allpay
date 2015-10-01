<?php

namespace Recca0120\AllPay\Message\Traits;

trait ChoosePaymentATM
{
    public function setExpireDate($value)
    {
        return $this->setParameter('ExpireDate', $value);
    }

    public function getExpireDate()
    {
        return $this->getParameter('ExpireDate');
    }

    public function setPaymentInfoURL($value)
    {
        return $this->setParameter('PaymentInfoURL', $value);
    }

    public function getPaymentInfoURL()
    {
        return $this->getParameter('PaymentInfoURL');
    }

    public function setClientRedirectURL($value)
    {
        return $this->setParameter('ClientRedirectURL', $value);
    }

    public function getClientRedirectURL()
    {
        return $this->getParameter('ClientRedirectURL');
    }
}
