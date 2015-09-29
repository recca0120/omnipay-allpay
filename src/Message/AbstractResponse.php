<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Message\AbstractResponse as baseAbstractResponse;

abstract class AbstractResponse extends baseAbstractResponse
{
    protected $successful = null;

    public function isSuccessful()
    {
        if ($this->successful === null) {
            $signature = $this->getRequest()->generateSignature($this->data);
            $this->successful = ($signature === $this->data['CheckMacValue']);
        }

        return $this->successful;
    }

    public function getTransactionReference()
    {
        return isset($this->data['TradeNo']) ? $this->data['TradeNo'] : null;
    }

    public function getMessage()
    {
        if ($this->isSuccessful() === true) {
            return $this->data['RtnMsg'];
        } else {
            return 'CheckMacValue Error';
        }
    }

    public function getCode()
    {
        if ($this->isSuccessful() === true) {
            return $this->data['RtnCode'];
        } else {
            return 10200073;
        }
    }
}
