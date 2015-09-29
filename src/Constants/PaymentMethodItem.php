<?php

namespace Recca0120\AllPay\Constants;

/**
 * 付款方式子項目。.
 */
abstract class PaymentMethodItem
{
    /**
     * 不指定。.
     */
    const NONE = '';
    // WebATM 類(001~100)
    /**
     * 台新銀行。.
     */
    const WEBATM_TAISHIN = 'TAISHIN';

    /**
     * 玉山銀行。.
     */
    const WEBATM_ESUN = 'ESUN';

    /**
     * 華南銀行。.
     */
    const WEBATM_HUANAN = 'HUANAN';

    /**
     * 台灣銀行。.
     */
    const WEBATM_BOT = 'BOT';

    /**
     * 台北富邦。.
     */
    const WEBATM_FUBON = 'FUBON';

    /**
     * 中國信託。.
     */
    const WEBATM_CHINATRUST = 'CHINATRUST';

    /**
     * 第一銀行。.
     */
    const WEBATM_FIRST = 'FIRST';

    /**
     * 國泰世華。.
     */
    const WEBATM_CATHAY = 'CATHAY';

    /**
     * 兆豐銀行。.
     */
    const WEBATM_MEGA = 'MEGA';

    /**
     * 元大銀行。.
     */
    const WEBATM_YUANTA = 'YUANTA';

    /**
     * 土地銀行。.
     */
    const WEBATM_LAND = 'LAND';
    // ATM 類(101~200)
    /**
     * 台新銀行。.
     */
    const ATM_TAISHIN = 'TAISHIN';

    /**
     * 玉山銀行。.
     */
    const ATM_ESUN = 'ESUN';

    /**
     * 華南銀行。.
     */
    const ATM_HUANAN = 'HUANAN';

    /**
     * 台灣銀行。.
     */
    const ATM_BOT = 'BOT';

    /**
     * 台北富邦。.
     */
    const ATM_FUBON = 'FUBON';

    /**
     * 中國信託。.
     */
    const ATM_CHINATRUST = 'CHINATRUST';

    /**
     * 第一銀行。.
     */
    const ATM_FIRST = 'FIRST';
    // 超商類(201~300)
    /**
     * 超商代碼繳款。.
     */
    const CVS = 'CVS';

    /**
     * OK超商代碼繳款。.
     */
    const CVS_OK = 'OK';

    /**
     * 全家超商代碼繳款。.
     */
    const CVS_FAMILY = 'FAMILY';

    /**
     * 萊爾富超商代碼繳款。.
     */
    const CVS_HILIFE = 'HILIFE';

    /**
     * 7-11 ibon代碼繳款。.
     */
    const CVS_IBON = 'IBON';
    // 其他第三方支付類(301~400)
    /**
     * 支付寶。.
     */
    const ALIPAY = 'Alipay';

    /**
     * 財付通。.
     */
    const TENPAY = 'Tenpay';
    // 儲值/餘額消費類(401~500)
    /**
     * 儲值/餘額消費(歐付寶).
     */
    const TOPUPUSED_ALLPAY = 'AllPay';

    /**
     * 儲值/餘額消費(玉山).
     */
    const TOPUPUSED_ESUN = 'ESUN';
    // 其他類(901~999)
    /**
     * 超商條碼繳款。.
     */
    const BARCODE = 'BARCODE';

    /**
     * 信用卡(MasterCard/JCB/VISA)。.
     */
    const CREDIT = 'Credit';

    /**
     * 貨到付款。.
     */
    const COD = 'COD';
}
