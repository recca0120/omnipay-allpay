<?php

namespace Recca0120\AllPay\Message\Traits;

trait ExtraPaidInfoBarcode
{
    public function setPaymentNo($value)
    {
        return $this->setParameter('PaymentNo', $value);
    }

    public function getPaymentNo()
    {
        return $this->getParameter('PaymentNo');
    }

    public function setPayFrom($value)
    {
        return $this->setParameter('PayFrom', $value);
    }

    public function getPayFrom()
    {
        return $this->getParameter('PayFrom');
    }
}
