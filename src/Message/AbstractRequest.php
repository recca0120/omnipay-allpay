<?php

namespace Recca0120\AllPay\Message;

// use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\RuntimeException;
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
use Omnipay\Common\Message\AbstractRequest as baseAbstractRequest;
use Recca0120\AllPay\Helper;
use Recca0120\AllPay\ItemBag;
use Symfony\Component\HttpFoundation\ParameterBag;

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

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     * @throws RuntimeException
     */
    public function initialize(array $parameters = [])
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }

        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    public function getItems()
    {
        return $this->getParameter('Items');
    }

    public function setItems($items)
    {
        if ($items && ! $items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('Items', $items);
    }

    public function generateSignature($data, $skipParameters = [])
    {
        $hashKey = $this->getHashKey();
        $hashIV = $this->getHashIV();
        // $testMode = $request->getTestMode();
        return Helper::generateSignature($hashKey, $hashIV, $data, $skipParameters);
    }

    public function getTransactionReference()
    {
        return $this->getMerchantTradeNo();
    }

    public function setTransactionReference($value)
    {
        return $this->setMerchantTradeNo($value);
    }

    public function setDescription($value)
    {
        return $this->setTradeDesc($value);
    }

    public function getDescription()
    {
        return $this->getTradeDesc();
    }

    public function getMerchantTradeNo()
    {
        return $this->getParameter('MerchantTradeNo');
    }

    public function setMerchantTradeNo($value)
    {
        return $this->setParameter('MerchantTradeNo', $value);
    }

    public function setTradeDesc($value)
    {
        return $this->setParameter('TradeDesc', $value);
    }

    public function getTradeDesc()
    {
        return $this->setParameter('TradeDesc');
    }

    public function setHashKey($value)
    {
        return $this->setParameter('HashKey', $value);
    }

    public function getHashKey()
    {
        return $this->getParameter('HashKey');
    }

    public function setHashIV($value)
    {
        return $this->setParameter('HashIV', $value);
    }

    public function getHashIV()
    {
        return $this->getParameter('HashIV');
    }

    public function setMerchantID($value)
    {
        return $this->setParameter('MerchantID', $value);
    }

    public function getMerchantID()
    {
        return $this->getParameter('MerchantID');
    }
}
