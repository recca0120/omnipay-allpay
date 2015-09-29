<?php

namespace Recca0120\AllPay\Message;

// use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as baseAbstractRequest;
// use Recca0120\AllPay\Constants\ActionType;
// use Recca0120\AllPay\Constants\CarruerType;
// use Recca0120\AllPay\Constants\ClearanceMark;
// use Recca0120\AllPay\Constants\DeviceType;
// use Recca0120\AllPay\Constants\Donation;
// use Recca0120\AllPay\Constants\ExtraPaymentInfo;
// use Recca0120\AllPay\Constants\InvoiceState;
// use Recca0120\AllPay\Constants\PaymentMethod;
// use Recca0120\AllPay\Constants\PaymentMethodItem;
// use Recca0120\AllPay\Constants\PrintMark;
// use Recca0120\AllPay\Constants\TaxType;
use Recca0120\AllPay\Helper;
use Recca0120\AllPay\ItemBag;

abstract class AbstractRequest extends baseAbstractRequest
{
    public $testEndPoint = '';
    public $liveEndPoint = '';

    public function getEndPoint()
    {
        return $this->getTestMode() ? $this->testEndPoint : $this->liveEndPoint;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function setItems($items)
    {
        if ($items && ! $items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    public function generateSignature($data, $skipParameters = [])
    {
        $hashKey = $this->getHashKey();
        $hashIV = $this->getHashIV();
        // $testMode = $request->getTestMode();
        return Helper::generateSignature($hashKey, $hashIV, $data, $skipParameters);
    }

    public function getMerchantTradeNo()
    {
        return $this->getTransactionReference();
    }

    public function setMerchantTradeNo($value)
    {
        return $this->setTransactionReference($value);
    }

    public function setTradeDesc($value)
    {
        return $this->setDescription($value);
    }

    public function getTradeDesc()
    {
        return $this->getDescription();
    }

    public function setHashKey($value)
    {
        return $this->setParameter('hashKey', $value);
    }

    public function getHashKey()
    {
        return $this->getParameter('hashKey');
    }

    public function setHashIV($value)
    {
        return $this->setParameter('hashIV', $value);
    }

    public function getHashIV()
    {
        return $this->getParameter('hashIV');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }
}
