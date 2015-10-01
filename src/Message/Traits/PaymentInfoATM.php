<?php

namespace Recca0120\AllPay\Message\Traits;

trait PaymentInfoATM
{
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
}
