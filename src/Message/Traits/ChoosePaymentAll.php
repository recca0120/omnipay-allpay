<?php

namespace Recca0120\AllPay\Message\Traits;

trait ChoosePaymentAll
{
    public function setMerchantID($value)
    {
        return $this->setParameter('MerchantID', $value);
    }

    public function getMerchantID()
    {
        return $this->getParameter('MerchantID');
    }

    public function setMerchantTradeNo($value)
    {
        return $this->setParameter('MerchantTradeNo', $value);
    }

    public function getMerchantTradeNo()
    {
        return $this->getParameter('MerchantTradeNo');
    }

    public function setMerchantTradeDate($value)
    {
        return $this->setParameter('MerchantTradeDate', $value);
    }

    public function getMerchantTradeDate()
    {
        return $this->getParameter('MerchantTradeDate');
    }

    public function setPaymentType($value)
    {
        return $this->setParameter('PaymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('PaymentType');
    }

    public function setTotalAmount($value)
    {
        return $this->setParameter('TotalAmount', $value);
    }

    public function getTotalAmount()
    {
        return $this->getParameter('TotalAmount');
    }

    public function setTradeDesc($value)
    {
        return $this->setParameter('TradeDesc', $value);
    }

    public function getTradeDesc()
    {
        return $this->getParameter('TradeDesc');
    }

    public function setItemName($value)
    {
        return $this->setParameter('ItemName', $value);
    }

    public function getItemName()
    {
        return $this->getParameter('ItemName');
    }

    public function setReturnURL($value)
    {
        return $this->setParameter('ReturnURL', $value);
    }

    public function getReturnURL()
    {
        return $this->getParameter('ReturnURL');
    }

    public function setChoosePayment($value)
    {
        return $this->setParameter('ChoosePayment', $value);
    }

    public function getChoosePayment()
    {
        return $this->getParameter('ChoosePayment');
    }

    public function setCheckMacValue($value)
    {
        return $this->setParameter('CheckMacValue', $value);
    }

    public function getCheckMacValue()
    {
        return $this->getParameter('CheckMacValue');
    }

    public function setClientBackURL($value)
    {
        return $this->setParameter('ClientBackURL', $value);
    }

    public function getClientBackURL()
    {
        return $this->getParameter('ClientBackURL');
    }

    public function setItemURL($value)
    {
        return $this->setParameter('ItemURL', $value);
    }

    public function getItemURL()
    {
        return $this->getParameter('ItemURL');
    }

    public function setRemark($value)
    {
        return $this->setParameter('Remark', $value);
    }

    public function getRemark()
    {
        return $this->getParameter('Remark');
    }

    public function setChooseSubPayment($value)
    {
        return $this->setParameter('ChooseSubPayment', $value);
    }

    public function getChooseSubPayment()
    {
        return $this->getParameter('ChooseSubPayment');
    }

    public function setOrderResultURL($value)
    {
        return $this->setParameter('OrderResultURL', $value);
    }

    public function getOrderResultURL()
    {
        return $this->getParameter('OrderResultURL');
    }

    public function setNeedExtraPaidInfo($value)
    {
        return $this->setParameter('NeedExtraPaidInfo', $value);
    }

    public function getNeedExtraPaidInfo()
    {
        return $this->getParameter('NeedExtraPaidInfo');
    }

    public function setDeviceSource($value)
    {
        return $this->setParameter('DeviceSource', $value);
    }

    public function getDeviceSource()
    {
        return $this->getParameter('DeviceSource');
    }

    public function setIgnorePayment($value)
    {
        return $this->setParameter('IgnorePayment', $value);
    }

    public function getIgnorePayment()
    {
        return $this->getParameter('IgnorePayment');
    }

    public function setPlatformID($value)
    {
        return $this->setParameter('PlatformID', $value);
    }

    public function getPlatformID()
    {
        return $this->getParameter('PlatformID');
    }

    public function setInvoiceMark($value)
    {
        return $this->setParameter('InvoiceMark', $value);
    }

    public function getInvoiceMark()
    {
        return $this->getParameter('InvoiceMark');
    }

    public function setHoldTradeAMT($value)
    {
        return $this->setParameter('HoldTradeAMT', $value);
    }

    public function getHoldTradeAMT()
    {
        return $this->getParameter('HoldTradeAMT');
    }

    public function setAllPayID($value)
    {
        return $this->setParameter('AllPayID', $value);
    }

    public function getAllPayID()
    {
        return $this->getParameter('AllPayID');
    }

    public function setAccountID($value)
    {
        return $this->setParameter('AccountID', $value);
    }

    public function getAccountID()
    {
        return $this->getParameter('AccountID');
    }

    public function setEncryptType($value)
    {
        return $this->setParameter('EncryptType', $value);
    }

    public function getEncryptType()
    {
        return $this->getParameter('EncryptType');
    }
}
