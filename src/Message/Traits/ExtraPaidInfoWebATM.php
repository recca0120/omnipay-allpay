<?php

namespace Recca0120\AllPay\Message\Traits;

trait ExtraPaidInfoWebATM
{
    public function setWebATMAccBank($value)
    {
        return $this->setParameter('WebATMAccBank', $value);
    }

    public function getWebATMAccBank()
    {
        return $this->getParameter('WebATMAccBank');
    }

    public function setWebATMAccNo($value)
    {
        return $this->setParameter('WebATMAccNo', $value);
    }

    public function getWebATMAccNo()
    {
        return $this->getParameter('WebATMAccNo');
    }

    public function setWebATMBankName($value)
    {
        return $this->setParameter('WebATMBankName', $value);
    }

    public function getWebATMBankName()
    {
        return $this->getParameter('WebATMBankName');
    }
}
