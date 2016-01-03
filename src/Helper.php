<?php

/**
 * Helper class.
 */
namespace Recca0120\AllPay;

use Omnipay\Common\Helper as baseHelper;

class Helper extends baseHelper
{
    /**
     * Initialize an object with a given array of parameters.
     *
     * Parameters are automatically converted to camelCase. Any parameters which do
     * not match a setter on the target object are ignored.
     *
     * @param mixed $target     The object to set parameters on
     * @param array $parameters An array of parameters to set
     */
    public static function initialize($target, $parameters)
    {
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                // $method = 'set'.ucfirst(static::camelCase($key));
                $method = 'set'.ucfirst($key);
                if (method_exists($target, $method)) {
                    $target->$method($value);
                }
            }
        }
    }

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

    public static function skipParameters($parameters, $skipParameters = [])
    {
        $skipParameters = array_merge([
            'HashKey',
            'HashIV',
            'testMode',
        ], $skipParameters);

        foreach ($skipParameters as $value) {
            if (isset($parameters[$value]) === true) {
                unset($parameters[$value]);
            }
        }

        return $parameters;
    }

    public static function aliases($parameters)
    {
        $data = [];

        $aliases = [
            'amount'               => 'TotalAmount',
            'description'          => 'TradeDesc',
            'transactionId'        => 'TradeNo',
            'transactionReference' => 'MerchantTradeNo',
        ];

        foreach ($parameters as $key => $value) {
            switch ($key) {
                // case 'currency':
                //     $data[ucfirst($key)] = static::currencyAlias($value);
                //     break;
                case 'items':
                case 'Items':
                    $items = [];
                    foreach ($value as $item) {
                        $items[] = [
                            'Name'     => $item->getName(),
                            'Price'    => $item->getPrice(),
                            'Currency' => static::currencyAlias($item->getCurrency()),
                            'Quantity' => $item->getQuantity(),
                            'URL'      => $item->getUrl(),
                        ];
                    }
                    $data['Items'] = $items;
                    break;
                default:
                    if (isset($aliases[$key])) {
                        $data[$aliases[$key]] = $value;
                    } else {
                        $data[$key] = $value;
                    }
                    break;
            }
        }
        ksort($data, SORT_NATURAL | SORT_FLAG_CASE);

        return $data;
    }

    public static function generateSignature($hashKey, $hashIV, $parameters, $skipParameters = [])
    {
        $skipParameters = array_merge([
            'testMode',
            'HashKey',
            'HashIV',
            'CheckMacValue',
        ], $skipParameters);

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
        return strtoupper(md5($signature));
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
public function set{$value}(\$value) {
    return \$this->setParameter('{$value}', \$value);
}

public function get{$value}() {
    return \$this->getParameter('{$value}');
}


EOF;
        }
        echo '<pre>';
        echo $html;
        echo '</pre>';
        // dump($html);
    }
}
