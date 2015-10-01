<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class RedirectResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
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
}
