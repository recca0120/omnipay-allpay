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
        return $this->setParameter('gwsr');
    }

    public function getGwsr()
    {
        return $this->getParameter('gwsr');
    }

    public function setProcessDate($value)
    {
        return $this->setParameter('process_date');
    }

    public function getProcessDate()
    {
        return $this->getParameter('process_date');
    }

    public function setAuthCode($value)
    {
        return $this->setParameter('auth_code');
    }

    public function getAuthCode()
    {
        return $this->getParameter('auth_code');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount');
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setStage($value)
    {
        return $this->setParameter('stage');
    }

    public function getStage()
    {
        return $this->getParameter('stage');
    }

    public function setStast($value)
    {
        return $this->setParameter('stast');
    }

    public function getStast()
    {
        return $this->getParameter('stast');
    }

    public function setStaed($value)
    {
        return $this->setParameter('staed');
    }

    public function getStaed()
    {
        return $this->getParameter('staed');
    }
    public function setEci($value)
    {
        return $this->setParameter('eci');
    }

    public function getEci()
    {
        return $this->getParameter('eci');
    }

    public function setCard4no($value)
    {
        return $this->setParameter('card4no');
    }

    public function getCard4no()
    {
        return $this->getParameter('card4no');
    }

    public function setCard6no($value)
    {
        return $this->setParameter('card6no');
    }

    public function getCard6no()
    {
        return $this->getParameter('card6no');
    }

    public function setRedDan($value)
    {
        return $this->setParameter('red_dan');
    }

    public function getRedDan()
    {
        return $this->getParameter('red_dan');
    }

    public function setRedDeAmt($value)
    {
        return $this->setParameter('red_de_amt');
    }

    public function getRedDeAmt()
    {
        return $this->getParameter('red_de_amt');
    }

    public function setRedOkAmt($value)
    {
        return $this->setParameter('red_ok_amt');
    }

    public function getRedOkAmt()
    {
        return $this->getParameter('red_ok_amt');
    }

    public function setRedYet($value)
    {
        return $this->setParameter('red_yet');
    }

    public function getRedYet()
    {
        return $this->getParameter('red_yet');
    }

    public function setPeriodType($value)
    {
        return $this->setParameter('PeriodType');
    }

    public function getPeriodType()
    {
        return $this->getParameter('PeriodType');
    }

    public function setFrequency($value)
    {
        return $this->setParameter('Frequency');
    }

    public function getFrequency()
    {
        return $this->getParameter('Frequency');
    }

    public function setExecTimes($value)
    {
        return $this->setParameter('ExecTimes');
    }

    public function getExecTimes()
    {
        return $this->getParameter('ExecTimes');
    }

    public function setPeriodAmount($value)
    {
        return $this->setParameter('PeriodAmount');
    }

    public function getPeriodAmount()
    {
        return $this->getParameter('PeriodAmount');
    }

    public function setTotalSuccessTimes($value)
    {
        return $this->setParameter('TotalSuccessTimes');
    }

    public function getTotalSuccessTimes()
    {
        return $this->getParameter('TotalSuccessTimes');
    }

    public function setTotalSuccessAmount($value)
    {
        return $this->setParameter('TotalSuccessAmount');
    }

    public function getTotalSuccessAmount()
    {
        return $this->getParameter('TotalSuccessAmount');
    }
}
