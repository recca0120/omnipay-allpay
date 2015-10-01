<?php

namespace Recca0120\AllPay\Message\Traits;

trait ExtraPaidInfoATM
{
    public function setATMAccBank($value)
    {
        return $this->setParameter('ATMAccBank', $value);
    }

    public function getATMAccBank()
    {
        return $this->getParameter('ATMAccBank');
    }

    public function setATMAccNo($value)
    {
        return $this->setParameter('ATMAccNo', $value);
    }

    public function getATMAccNo()
    {
        return $this->getParameter('ATMAccNo');
    }
}
