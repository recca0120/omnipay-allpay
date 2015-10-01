<?php

namespace Recca0120\AllPay\Message\Traits;

trait InvoiceMark
{
    public function setRelateNumber($value)
    {
        return $this->setParameter('RelateNumber', $value);
    }

    public function getRelateNumber()
    {
        return $this->getParameter('RelateNumber');
    }

    public function setCustomerID($value)
    {
        return $this->setParameter('CustomerID', $value);
    }

    public function getCustomerID()
    {
        return $this->getParameter('CustomerID');
    }

    public function setCustomerIdentifier($value)
    {
        return $this->setParameter('CustomerIdentifier', $value);
    }

    public function getCustomerIdentifier()
    {
        return $this->getParameter('CustomerIdentifier');
    }

    public function setCustomerName($value)
    {
        return $this->setParameter('CustomerName', $value);
    }

    public function getCustomerName()
    {
        return $this->getParameter('CustomerName');
    }

    public function setCustomerAddr($value)
    {
        return $this->setParameter('CustomerAddr', $value);
    }

    public function getCustomerAddr()
    {
        return $this->getParameter('CustomerAddr');
    }

    public function setCustomerPhone($value)
    {
        return $this->setParameter('CustomerPhone', $value);
    }

    public function getCustomerPhone()
    {
        return $this->getParameter('CustomerPhone');
    }

    public function setCustomerEmail($value)
    {
        return $this->setParameter('CustomerEmail', $value);
    }

    public function getCustomerEmail()
    {
        return $this->getParameter('CustomerEmail');
    }

    public function setClearanceMark($value)
    {
        return $this->setParameter('ClearanceMark', $value);
    }

    public function getClearanceMark()
    {
        return $this->getParameter('ClearanceMark');
    }

    public function setTaxType($value)
    {
        return $this->setParameter('TaxType', $value);
    }

    public function getTaxType()
    {
        return $this->getParameter('TaxType');
    }

    public function setCarruerType($value)
    {
        return $this->setParameter('CarruerType', $value);
    }

    public function getCarruerType()
    {
        return $this->getParameter('CarruerType');
    }

    public function setCarruerNum($value)
    {
        return $this->setParameter('CarruerNum', $value);
    }

    public function getCarruerNum()
    {
        return $this->getParameter('CarruerNum');
    }

    public function setDonation($value)
    {
        return $this->setParameter('Donation', $value);
    }

    public function getDonation()
    {
        return $this->getParameter('Donation');
    }

    public function setLoveCode($value)
    {
        return $this->setParameter('LoveCode', $value);
    }

    public function getLoveCode()
    {
        return $this->getParameter('LoveCode');
    }

    public function setPrint($value)
    {
        return $this->setParameter('Print', $value);
    }

    public function getPrint()
    {
        return $this->getParameter('Print');
    }

    public function setInvoiceItemName($value)
    {
        return $this->setParameter('InvoiceItemName', $value);
    }

    public function getInvoiceItemName()
    {
        return $this->getParameter('InvoiceItemName');
    }

    public function setInvoiceItemCount($value)
    {
        return $this->setParameter('InvoiceItemCount', $value);
    }

    public function getInvoiceItemCount()
    {
        return $this->getParameter('InvoiceItemCount');
    }

    public function setInvoiceItemWord($value)
    {
        return $this->setParameter('InvoiceItemWord', $value);
    }

    public function getInvoiceItemWord()
    {
        return $this->getParameter('InvoiceItemWord');
    }

    public function setInvoiceItemPrice($value)
    {
        return $this->setParameter('InvoiceItemPrice', $value);
    }

    public function getInvoiceItemPrice()
    {
        return $this->getParameter('InvoiceItemPrice');
    }

    public function setInvoiceItemTaxType($value)
    {
        return $this->setParameter('InvoiceItemTaxType', $value);
    }

    public function getInvoiceItemTaxType()
    {
        return $this->getParameter('InvoiceItemTaxType');
    }

    public function setInvoiceRemark($value)
    {
        return $this->setParameter('InvoiceRemark', $value);
    }

    public function getInvoiceRemark()
    {
        return $this->getParameter('InvoiceRemark');
    }

    public function setDelayDay($value)
    {
        return $this->setParameter('DelayDay', $value);
    }

    public function getDelayDay()
    {
        return $this->getParameter('DelayDay');
    }

    public function setInvType($value)
    {
        return $this->setParameter('InvType', $value);
    }

    public function getInvType()
    {
        return $this->getParameter('InvType');
    }
}
