<?php

namespace Recca0120\AllPay\Constants;

/**
 * 付款方式。.
 */
abstract class PaymentMethod
{
    /**
     * 不指定付款方式。.
     */
    const ALL = 'ALL';

    /**
     * 信用卡付費。.
     */
    const CREDIT = 'Credit';

    /**
     * 網路 ATM。.
     */
    const WEBATM = 'WebATM';

    /**
     * 自動櫃員機。.
     */
    const ATM = 'ATM';

    /**
     * 超商代碼。.
     */
    const CVS = 'CVS';

    /**
     * 超商條碼。.
     */
    const BARCODE = 'BARCODE';

    /**
     * 支付寶。.
     */
    const ALIPAY = 'Alipay';

    /**
     * 財付通。.
     */
    const TENPAY = 'Tenpay';

    /**
     * 儲值消費。.
     */
    const TOPUPUSED = 'TopUpUsed';
}
