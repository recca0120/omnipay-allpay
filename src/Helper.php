<?php

/**
 * Helper class.
 */
namespace Recca0120\AllPay;

use Omnipay\Common\Helper as baseHelper;

class Helper extends baseHelper
{
    public static function currencyAlias($currencyCode)
    {
        $currencyCode = strtoupper($currencyCode);
        $aliases = [
            'TWD' => '元',
        ];

        if (isset($aliases[$currencyCode]) === true) {
            return $aliases[$currencyCode];
        }

        return $currencyCode;
    }

    public static function aliases($parameters, $skipParameters = [])
    {
        $data = [];
        $aliases = [
            'merchantId' => 'MerchantID',
            'returnUrl' => 'ReturnURL',
            'clientBackUrl' => 'ClientBackURL',
            'orderResultUrl' => 'OrderResultURL',
            // 'transactionId' => 'MerchantTradeNo',
            'transactionReference' => 'MerchantTradeNo',
            'amount' => 'TotalAmount',
            'description' => 'TradeDesc',
            'extraPaidInfo' => 'NeedExtraPaidInfo',
            'platformId' => 'PlatformID',
            'clientRedirectUrl' => 'ClientRedirectURL',
            'paymentInfoUrl' => 'PaymentInfoURL',
            'periodReturnUrl' => 'PeriodReturnURL',
            'customerId' => 'CustomerID',
            'desc1' => 'Desc_1',
            'desc2' => 'Desc_2',
            'desc3' => 'Desc_3',
            'desc4' => 'Desc_4',
        ];

        $skipParameters = array_merge([
            'hashKey',
            'hashIV',
            'testMode',
        ], $skipParameters);

        foreach ($skipParameters as $value) {
            if (isset($parameters[$value]) === true) {
                unset($parameters[$value]);
            }
        }

        foreach ($parameters as $key => $value) {
            switch ($key) {
                // case 'currency':
                //     $data[ucfirst($key)] = static::currencyAlias($value);
                //     break;
                case 'items':
                    $items = [];
                    foreach ($value as $item) {
                        $itemCurrency = $item->getCurrency();
                        $items[] = [
                            'Name' => $item->getName(),
                            'Price' => $item->getPrice(),
                            'Currency' => ($itemCurrency === 'TWD') ? '元' : $itemCurrency,
                            'Quantity' => $item->getQuantity(),
                            'URL' => $item->getUrl(),
                        ];
                    }
                    $data['Items'] = $items;
                    break;
                default:
                    if (isset($aliases[$key]) === true) {
                        $data[$aliases[$key]] = $value;
                    } else {
                        $data[ucfirst($key)] = $value;
                    }
                    break;
            }
        }
        ksort($data, SORT_NATURAL | SORT_FLAG_CASE);

        return $data;
    }

    public static function generateSignature($hashKey, $hashIV, $parameters, $skipParameters = [])
    {
        $skipParameters = array_merge(['CheckMacValue'], $skipParameters);

        foreach ($skipParameters as $value) {
            if (isset($parameters[$value]) === true) {
                unset($parameters[$value]);
            }
        }

        # 調整ksort排序規則--依自然排序法(大小寫不敏感)
        ksort($parameters, SORT_NATURAL | SORT_FLAG_CASE);
        $signature = 'HashKey='.$hashKey;
        foreach ($parameters as $key => $value) {
            // Customize to Skip Parameters for HikaShop
            // Customize to Skip Parameters for MijoShop
            if (in_array($key, ['view', 'hikashop_front_end_main', 'mijoshop_store_id', 'language'], true) === true) {
                continue;
            }
            $signature .= '&'.$key.'='.$value;
        }
        $signature .= '&HashIV='.$hashIV;
        $signature = strtolower(urlencode($signature));
        $signature = static::replaceChars($signature);

        // MD5 編碼
        return md5($signature);
    }

    protected static function replaceChars($value)
    {
        // 取代為與 dotNet 相符的字元
        $search_list = ['%2d', '%5f', '%2e', '%21', '%2a', '%28', '%29'];
        $replace_list = ['-', '_', '.', '!', '*', '(', ')'];
        $value = str_replace($search_list, $replace_list, $value);
        if (session_status() == PHP_SESSION_NONE) {
            // Customize for Magento
            $value = str_replace('%3f___sid%3d'.session_id(), '', $value);
            $value = str_replace('%3f___sid%3du', '', $value);
            $value = str_replace('%3f___sid%3ds', '', $value);
        }

        return $value;
    }

    public static function purchaseData($data)
    {
        $temp = [];
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'PaymentType':
                    $value = str_replace([
                        '_CVS',
                        '_BARCODE',
                        '_Alipay',
                        '_Tenpay',
                        '_CreditCard',
                    ], '', $value);
                    break;
                case 'PeriodType':
                    $value = str_replace([
                        'Y',
                        'M',
                        'D',
                    ], [
                        'Year',
                        'Month',
                        'Day',
                    ], $value);
                    break;
            }
            $temp[$key] = $value;
        }

        return $temp;
    }

    public static function dumpMethods($obj, $parameters = [])
    {
        ksort($parameters, SORT_NATURAL | SORT_FLAG_CASE);
        // echo '<pre>';
        $html = '';
        foreach ($parameters as $key => $value) {
            $uc = ucfirst($value);
            $lc = lcfirst($value);
            if (method_exists($obj, 'set'.$uc) === true) {
                continue;
            }

            $html .= <<<EOF
public function set{$uc}(\$value) {
    return \$this->setParameter('{$lc}', \$value);
}

public function get{$uc}() {
    return \$this->getParameter('{$lc}');
}


EOF;
        }
        echo '<pre>';
        echo $html;
        echo '</pre>';
        // dump($html);
    }
}
