<?php

namespace Recca0120\AllPay\Message\Traits;

trait ChoosePaymentCredit
{
    public function setCreditInstallment($value)
    {
        return $this->setParameter('CreditInstallment', $value);
    }

    public function getCreditInstallment()
    {
        return $this->getParameter('CreditInstallment');
    }

    public function setInstallmentAmount($value)
    {
        return $this->setParameter('InstallmentAmount', $value);
    }

    public function getInstallmentAmount()
    {
        return $this->getParameter('InstallmentAmount');
    }

    public function setRedeem($value)
    {
        return $this->setParameter('Redeem', $value);
    }

    public function getRedeem()
    {
        return $this->getParameter('Redeem');
    }

    public function setUnionPay($value)
    {
        return $this->setParameter('UnionPay', $value);
    }

    public function getUnionPay()
    {
        return $this->getParameter('UnionPay');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('Language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('Language');
    }
}
