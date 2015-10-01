<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Message\AbstractResponse as baseAbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

abstract class AbstractResponse extends baseAbstractResponse implements RedirectResponseInterface
{
    protected $successful = null;

    public function isSuccessful()
    {
        $request = $this->getRequest();
        if ($this->successful === null) {
            if ($request instanceof CompleteAuthorizeRequest) {
                $signature = $this->getRequest()->generateSignature($this->data);
                $this->successful = ($signature === $this->data['CheckMacValue']);
                if ($this->successful === false) {
                    $this->data['RtnCode'] = '10200073';
                    $this->data['RtnMsg'] = 'CheckMacValue Error';
                }
            } elseif ($request instanceof RefundRequest) {
                $this->successful = ($this->data['RtnCode'] === '1');
            } elseif ($request instanceof AuthorizeRequest) {
                return false;
            }
        }

        return $this->successful;
    }

    public function isRedirect()
    {
        $request = $this->getRequest();

        return ($request instanceof AuthorizeRequest);
    }

    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndPoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return array_merge($this->getData(), [
            'CheckMacValue' => $this->getRequest()->generateSignature($this->data),
        ]);
    }

    public function redirect()
    {
        if (! headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }

        return parent::redirect();
    }

    public function getTransactionId()
    {
        return isset($this->data['TradeNo']) ? $this->data['TradeNo'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['MerchantTradeNo']) ? $this->data['MerchantTradeNo'] : null;
    }

    public function getMessage()
    {
        return $this->data['RtnMsg'];
    }

    public function getCode()
    {
        return $this->data['RtnCode'];
    }
}
