<?php

namespace Recca0120\AllPay\Message\Traits;

// ChoosePaymentCVS
trait ChoosePaymentBarcode
{
    public function setStoreExpireDate($value)
    {
        return $this->setParameter('StoreExpireDate', $value);
    }

    public function getStoreExpireDate($value)
    {
        return $this->getParameter('StoreExpireDate');
    }

    public function setDesc1($value)
    {
        return $this->setParameter('Desc_1', $value);
    }

    public function getDesc1($value)
    {
        return $this->getParameter('Desc_1');
    }

    public function setDesc2($value)
    {
        return $this->setParameter('Desc_2', $value);
    }

    public function getDesc2($value)
    {
        return $this->getParameter('Desc_2');
    }

    public function setDesc3($value)
    {
        return $this->setParameter('Desc_3', $value);
    }

    public function getDesc3($value)
    {
        return $this->getParameter('Desc_3');
    }

    public function setDesc4($value)
    {
        return $this->setParameter('Desc_4', $value);
    }

    public function getDesc4($value)
    {
        return $this->getParameter('Desc_4');
    }

    public function setPaymentInfoURL($value)
    {
        return $this->setParameter('PaymentInfoURL', $value);
    }

    public function getPaymentInfoURL($value)
    {
        return $this->getParameter('PaymentInfoURL');
    }

    public function setClientRedirectURL($value)
    {
        return $this->setParameter('ClientRedirectURL', $value);
    }

    public function getClientRedirectURL($value)
    {
        return $this->getParameter('ClientRedirectURL');
    }
}
