<?php

namespace Recca0120\AllPay\Message\Traits;

trait ChoosePaymentAlipay
{
    public function setAlipayItemName($value)
    {
        return $this->setParameter('AlipayItemName', $value);
    }

    public function getAlipayItemName()
    {
        return $this->getParameter('AlipayItemName');
    }

    public function setAlipayItemCounts($value)
    {
        return $this->setParameter('AlipayItemCounts', $value);
    }

    public function getAlipayItemCounts()
    {
        return $this->getParameter('AlipayItemCounts');
    }

    public function setAlipayItemPrice($value)
    {
        return $this->setParameter('AlipayItemPrice', $value);
    }

    public function getAlipayItemPrice()
    {
        return $this->getParameter('AlipayItemPrice');
    }

    public function setEmail($value)
    {
        return $this->setParameter('Email', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('Email');
    }

    public function setPhoneNo($value)
    {
        return $this->setParameter('PhoneNo', $value);
    }

    public function getPhoneNo()
    {
        return $this->getParameter('PhoneNo');
    }

    public function setUserName($value)
    {
        return $this->setParameter('UserName', $value);
    }

    public function getUserName()
    {
        return $this->getParameter('UserName');
    }
}
