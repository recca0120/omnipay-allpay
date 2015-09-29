<?php

namespace Recca0120\AllPay;

use Omnipay\Common\Item as baseItem;

class Item extends baseItem
{
    public function __construct($parameters = null)
    {
        $parameters = array_merge([
            'currency' => 'TWD',
            'url' => null,
        ], $parameters);
        $this->initialize($parameters);
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setUrl($value)
    {
        return $this->setParameter('url', $value);
    }

    public function getUrl()
    {
        return $this->getParameter('url');
    }
}
