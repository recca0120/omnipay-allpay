<?php

namespace Recca0120\AllPay\Message\Traits;

trait PaymentInfoBarcode
{
    public function setPaymentNo($value)
    {
        return $this->setParameter('PaymentNo', $value);
    }

    public function getPaymentNo()
    {
        return $this->getParameter('PaymentNo');
    }

    public function setExpireDate($value)
    {
        return $this->setParameter('ExpireDate', $value);
    }

    public function getExpireDate()
    {
        return $this->getParameter('ExpireDate');
    }

    public function setBarcode1($value)
    {
        return $this->setParameter('Barcode1', $value);
    }

    public function getBarcode1()
    {
        return $this->getParameter('Barcode1');
    }

    public function setBarcode2($value)
    {
        return $this->setParameter('Barcode2', $value);
    }

    public function getBarcode2()
    {
        return $this->getParameter('Barcode2');
    }

    public function setBarcode3($value)
    {
        return $this->setParameter('Barcode3', $value);
    }

    public function getBarcode3()
    {
        return $this->getParameter('Barcode3');
    }
}
