<?php

namespace Recca0120\AllPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Currency;
use Recca0120\AllPay\Constants\DeviceType;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'AllPay';
    }

    public function getDefaultParameters()
    {
        // 特店編號(MerchantID) 2000132
        // 登入廠商後台帳號/密碼 StageTest/test1234
        // 廠商後台測試環境
        // http://vendor-stage.allpay.com.tw，此網站提供查詢測試的訂單相關資訊，
        // 也可執行訂單模擬付款的功能，送回付款通知的資訊到貴公司的網站。
        // 若執行模擬付款功能，無法收到付款通知時，請參考底下注意事項。
        // all in one 介接的 HashKey 5294y06JbISpM5x9
        // all in one 介接的 HashIV v77hoKGq4kWxNNIS
        // 信用卡測試卡號 4311-9522-2222-2222
        // 信用卡測試安全碼 222
        // 信用卡測試有效年月
        // 請設定大於測試時間。假如您的測試時間在 2013 年 11 月 26 號，該筆交易的
        // 信用卡有效年月請設定 2013 年 11 月以後，因為系統會判斷有效年月是否已過
        // 期，已過期則會回應刷卡失敗。
        return [
            'testMode' => false,
            'hashKey' => '5294y06JbISpM5x9',
            'hashIV' => 'v77hoKGq4kWxNNIS',
            'merchantId' => '2000132',
            // 'currency' => 'TWD',
            'deviceSource' => DeviceType::PC,
        ];
    }

    public function authorize(array $parameters = [])
    {
        return $this->createRequest('\\Recca0120\\AllPay\\Message\\AuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = [])
    {
        return $this->createRequest('\\Recca0120\\AllPay\\Message\\CompleteAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\\Recca0120\\AllPay\\Message\\AuthorizeRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\\Recca0120\\AllPay\\Message\\CompleteAuthorizeRequest', $parameters);
    }

    public function void(array $parameters = [])
    {
        return $this->createRequest('\\Recca0120\\AllPay\\Message\\VoidRequest', $parameters);
    }

    public function setDeviceSource($value)
    {
        return $this->setParameter('deviceSource', $value);
    }

    public function getDeviceSource()
    {
        return $this->getParameter('deviceSource');
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
