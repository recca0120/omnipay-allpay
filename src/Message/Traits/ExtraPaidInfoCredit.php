<?php

namespace Recca0120\AllPay\Message\Traits;

trait ExtraPaidInfoCredit
{
    // gwsr
    // process_date
    // auth_code
    // amount
    // stage
    // stast
    // staed
    // eci
    // card4no
    // card6no
    // red_dan
    // red_de_amt
    // red_ok_amt
    // red_yet
    // PeriodType
    // Frequency
    // ExecTimes
    // PeriodAmount
    // TotalSuccessTimes
    // TotalSuccessAmount

    public function setGwsr($value)
    {
        return $this->setParameter('gwsr', $value);
    }

    public function getGwsr()
    {
        return $this->getParameter('gwsr');
    }

    public function setProcessDate($value)
    {
        return $this->setParameter('process_date', $value);
    }

    public function getProcessDate()
    {
        return $this->getParameter('process_date');
    }

    public function setAuthCode($value)
    {
        return $this->setParameter('auth_code', $value);
    }

    public function getAuthCode()
    {
        return $this->getParameter('auth_code');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setStage($value)
    {
        return $this->setParameter('stage', $value);
    }

    public function getStage()
    {
        return $this->getParameter('stage');
    }

    public function setStast($value)
    {
        return $this->setParameter('stast', $value);
    }

    public function getStast()
    {
        return $this->getParameter('stast');
    }

    public function setStaed($value)
    {
        return $this->setParameter('staed', $value);
    }

    public function getStaed()
    {
        return $this->getParameter('staed');
    }

    public function setEci($value)
    {
        return $this->setParameter('eci', $value);
    }

    public function getEci()
    {
        return $this->getParameter('eci');
    }

    public function setCard4no($value)
    {
        return $this->setParameter('card4no', $value);
    }

    public function getCard4no()
    {
        return $this->getParameter('card4no');
    }

    public function setCard6no($value)
    {
        return $this->setParameter('card6no', $value);
    }

    public function getCard6no()
    {
        return $this->getParameter('card6no');
    }

    public function setRedDan($value)
    {
        return $this->setParameter('red_dan', $value);
    }

    public function getRedDan()
    {
        return $this->getParameter('red_dan');
    }

    public function setRedDeAmt($value)
    {
        return $this->setParameter('red_de_amt', $value);
    }

    public function getRedDeAmt()
    {
        return $this->getParameter('red_de_amt');
    }

    public function setRedOkAmt($value)
    {
        return $this->setParameter('red_ok_amt', $value);
    }

    public function getRedOkAmt()
    {
        return $this->getParameter('red_ok_amt');
    }

    public function setRedYet($value)
    {
        return $this->setParameter('red_yet', $value);
    }

    public function getRedYet()
    {
        return $this->getParameter('red_yet');
    }

    public function setPeriodType($value)
    {
        return $this->setParameter('PeriodType', $value);
    }

    public function getPeriodType()
    {
        return $this->getParameter('PeriodType');
    }

    public function setFrequency($value)
    {
        return $this->setParameter('Frequency', $value);
    }

    public function getFrequency()
    {
        return $this->getParameter('Frequency');
    }

    public function setExecTimes($value)
    {
        return $this->setParameter('ExecTimes', $value);
    }

    public function getExecTimes()
    {
        return $this->getParameter('ExecTimes');
    }

    public function setPeriodAmount($value)
    {
        return $this->setParameter('PeriodAmount', $value);
    }

    public function getPeriodAmount()
    {
        return $this->getParameter('PeriodAmount');
    }

    public function setTotalSuccessTimes($value)
    {
        return $this->setParameter('TotalSuccessTimes', $value);
    }

    public function getTotalSuccessTimes()
    {
        return $this->getParameter('TotalSuccessTimes');
    }

    public function setTotalSuccessAmount($value)
    {
        return $this->setParameter('TotalSuccessAmount', $value);
    }

    public function getTotalSuccessAmount()
    {
        return $this->getParameter('TotalSuccessAmount');
    }
}
