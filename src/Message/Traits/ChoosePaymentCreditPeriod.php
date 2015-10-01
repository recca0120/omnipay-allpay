<?php

namespace Recca0120\AllPay\Message\Traits;

trait ChoosePaymentCreditPeriod
{
    public function setPeriodAmount($value)
    {
        return $this->setParameter('PeriodAmount', $value);
    }

    public function getPeriodAmount($value)
    {
        return $this->getParameter('PeriodAmount');
    }

    public function setPeriodType($value)
    {
        return $this->setParameter('PeriodType', $value);
    }

    public function getPeriodType($value)
    {
        return $this->getParameter('PeriodType');
    }

    public function setFrequency($value)
    {
        return $this->setParameter('Frequency', $value);
    }

    public function getFrequency($value)
    {
        return $this->getParameter('Frequency');
    }

    public function setExecTimes($value)
    {
        return $this->setParameter('ExecTimes', $value);
    }

    public function getExecTimes($value)
    {
        return $this->getParameter('ExecTimes');
    }

    public function setPeriodReturnURL($value)
    {
        return $this->setParameter('PeriodReturnURL', $value);
    }

    public function getPeriodReturnURL($value)
    {
        return $this->getParameter('PeriodReturnURL');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('Language', $value);
    }

    public function getLanguage($value)
    {
        return $this->getParameter('Language');
    }
}
