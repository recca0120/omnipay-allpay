<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as baseAbstractRequest;
use Recca0120\AllPay\Constants\ActionType;
use Recca0120\AllPay\Constants\CarruerType;
use Recca0120\AllPay\Constants\ClearanceMark;
use Recca0120\AllPay\Constants\DeviceType;
use Recca0120\AllPay\Constants\Donation;
use Recca0120\AllPay\Constants\ExtraPaymentInfo;
use Recca0120\AllPay\Constants\InvoiceState;
use Recca0120\AllPay\Constants\PaymentMethod;
use Recca0120\AllPay\Constants\PaymentMethodItem;
use Recca0120\AllPay\Constants\PrintMark;
use Recca0120\AllPay\Constants\TaxType;
use Recca0120\AllPay\ItemBag;

abstract class AbstractRequestBak extends baseAbstractRequest
{
    public $testEndPoint = 'https://payment-stage.allpay.com.tw/Cashier/AioCheckOut';
    public $liveEndPoint = 'https://payment.allpay.com.tw/Cashier/AioCheckOut';

    public function initialize(array $parameters = [])
    {
        $parameters = array_merge($this->getDefaultParameters(), $parameters);

        return parent::initialize($parameters);
    }

    protected function getParameterAliases()
    {
        return [
            'merchantId' => 'MerchantID',
            'returnUrl' => 'ReturnURL',
            'clientBackUrl' => 'ClientBackURL',
            'orderResultUrl' => 'OrderResultURL',
            'transactionId' => 'MerchantTradeNo',
            'amount' => 'TotalAmount',
            'description' => 'TradeDesc',
            'paymentMethod' => 'ChoosePayment',
            'extraPaidInfo' => 'NeedExtraPaidInfo',
            'deviceType' => 'DeviceSource',
            'platformId' => 'PlatformID',
            'invoiceState' => 'InvoiceMark',

            'desc1' => 'Desc_1',
            'desc2' => 'Desc_2',
            'desc3' => 'Desc_3',
            'desc4' => 'Desc_4',

            // Alipay
            'email' => 'Email',
            'phoneNo' => 'PhoneNo',
            'userName' => 'UserName',

            'customerId' => 'customerID',
        ];
    }

    public function getDefaultParameters()
    {
        // 交易返回頁面
        // $return_url = 'http://www.allpay.com.tw/receive.php';
        // 交易通知網址
        // $client_back_url = 'http://www.allpay.com.tw/receive.php';
        $send = [
            'returnUrl' => 'http://www.allpay.com.tw/receive.php',
            'clientBackUrl' => 'http://www.allpay.com.tw/receive.php',
            'orderResultUrl' => '',
            // Alias MerchantTradeNo
            'transactionId' => '',
            'merchantTradeDate' => date('Y/m/d H:i:s'),
            'paymentType' => 'aio',
            // Alias TotalAmount
            'amount' => '',
            // Alias TradeDesc
            'description' => '',
            // Alias ChoosePayment
            'paymentMethod' => PaymentMethod::ALL,
            'remark' => '',
            'chooseSubPayment' => PaymentMethodItem::NONE,
            // Alias NeedExtraPaidInfo
            'extraPaidInfo' => ExtraPaymentInfo::NO,
            // Alias DeviceSource
            'deviceType' => DeviceType::PC,
            'ignorePayment' => '',
            'platformId' => '',
            // Alias InvoiceMark
            'invoiceState' => InvoiceState::NO,
            'items' => [],
        ];

        $sendExtend = [
            'expireDate' => 3,
            // CVS, BARCODE 延伸參數。
            // Alias Desc_1
            'desc1' => '',
            // Alias Desc_2
            'desc2' => '',
            // Alias Desc_3
            'desc3' => '',
            // Alias Desc_4
            'desc4' => '',
            // ATM, CVS, BARCODE 延伸參數。
            'clientRedirectUrl' => '',
            // Alipay 延伸參數。
            'email' => '',
            'PhoneNo' => '',
            'UserName' => '',
            // Tenpay 延伸參數。
            'expireTime' => '',
            // Credit 分期延伸參數。
            'creditInstallment' => 0,
            'installmentAmount' => 0,
            'redeem' => false,
            'unionPay' => false,
            // Credit 定期定額延伸參數。
            'periodAmount' => '',
            'periodType' => '',
            'frequency' => '',
            'execTimes' => '',
            // 回傳網址的延伸參數。
            'paymentInfoUrl' => '',
            'periodReturnUrl' => '',
            // 電子發票延伸參數。
            'customerIdentifier' => '',
            'carruerType' => CarruerType::NONE,
            'customerId' => '',
            'donation' => Donation::NO,
            'print' => PrintMark::NO,
            'customerName' => '',
            'customerAddr' => '',
            'customerPhone' => '',
            'customerEmail' => '',
            'clearanceMark' => '',
            'carruerNum' => '',
            'loveCode' => '',
            'invoiceRemark' => '',
            'invoiceItems' => [],
            'delayDay' => 0,
        ];

        // $query = [
        //     // Alias merchantTradeNo
        //     'transactionId' => '',
        //     'timeStamp' => '',
        // ];

        // $action = [
        //     // Alias merchantTradeNo
        //     'transactionId' => '',
        //     'TradeNo' => '',
        //     'Action' => ActionType::C,
        //     'TotalAmount' => 0,
        // ];

        // $chargeBack = [
        //     // Alias merchantTradeNo
        //     'transactionId' => '',
        //     'tradeNo' => '',
        //     'chargeBackTotalAmount' => 0,
        //     'remark' => '',
        // ];

        return array_merge($send, $sendExtend);
    }

    public function dumpMethods()
    {
        $parameters = array_merge([
            'hashKey' => '',
            'hashIV' => '',
        ], $this->getDefaultParameters());

        $html = '';
        foreach ($parameters as $key => $value) {
            $uc = ucfirst($key);
            $lc = lcfirst($key);
            if (method_exists($this, 'set'.$uc) === true) {
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
        echo $html;
        exit;
    }

    public function getEndPoint()
    {
        return $this->getTestMode() ? $this->testEndPoint : $this->liveEndPoint;
    }

    protected function required($data, $key)
    {
        if (empty($data[$key]) === true) {
            throw new InvalidRequestException($key.' is required.');
        }

        return true;
    }

    public function setItems($items)
    {
        if ($items && ! $items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    protected function maxLength($data, $key, $maxLength, $encoding = null)
    {
        if ($encoding !== null) {
            $length = mb_strlen($data[$key], $encoding);
        } else {
            $length = strlen($data[$key]);
        }
        if ($length > $maxLength) {
            throw new InvalidRequestException($key.' max langth as '.$maxLength.'.');
        }

        return true;
    }

    public function getData()
    {
        $this->validate(
            // 'merchantId',
            // 'hashKey',
            // 'hashIV',
            // 'merchantTradeDate',
            // 'extraPaidInfo',
            // 'deviceType'
            'returnUrl',
            'transactionId',
            'items',
            'description',
            'amount',
            'paymentMethod'
        );

        $data = [];
        $parameters = $this->getParameters();
        $aliases = $this->getParameterAliases();

        $skipParamerters = [
            'hashKey',
            'hashIV',
            'testMode',
        ];

        foreach ($parameters as $key => $value) {
            if (in_array($key, $skipParamerters, true) === true) {
                continue;
            }

            if (isset($aliases[$key]) === true) {
                $data[$aliases[$key]] = $value;
            } else {
                $data[ucfirst($key)] = $value;
            }

            // if (isset($aliases[$key]) === true) {
            //     if ($key === 'items') {
            //         // $value = implode('#', $value);
            //         $temp = [];
            //         foreach ($value as $item) {
            //             $itemName = $item->getName();
            //             $itemQuantity = $item->getQuantity();
            //             $itemPrice = $item->getQuantity();
            //             $itemDescription = $item->getDescription();
            //             // $itemName = vsprintf('#%s %d %s x %u', []);
            //             // $temp[] = $item->getName();
            //         }
            //         // dump($itemName);
            //         // $value = implode('#', $temp);
            //     }
            //     $data[$aliases[$key]] = $value;
            // } else {
            //     $data[ucfirst($key)] = $value;
            // }
        }

        $this->required($data, 'MerchantID');
        $this->maxLength($data, 'MerchantID', 10);

        $this->maxLength($data, 'ClientBackURL', 200);
        $this->maxLength($data, 'OrderResultURL', 200);

        $this->maxLength($data, 'MerchantTradeNo', 20);
        $this->maxLength($data, 'TradeDesc', 200);

        $this->required($data, 'ChoosePayment');
        switch ($data['ChoosePayment']) {
            case PaymentMethod::ALIPAY:
                $this->required($data, 'Email');
                $this->maxLength($data, 'Email', 200);
                $this->required($data, 'PhoneNo');
                $this->maxLength($data, 'PhoneNo', 20);
                $this->required($data, 'UserName');
                $this->maxLength($data, 'UserName', 20);
                break;
        }

        $temp = [
            'itemName' => '',
        ];
        foreach ($data['Items'] as $item) {
            $itemName = $item->getName();
            $itemPrice = $item->getPrice();
            $itemCurrency = $item->getCurrency();
            $itemQuantity = $item->getQuantity();
            $itemUrl = $item->getUrl();

            $temp['itemName'] .= vsprintf('#%s %d %s x %u', [
                $itemName,
                $itemPrice,
                $itemCurrency,
                $itemQuantity,
                $itemUrl,
            ]);
            // dump($itemName, $itemQuantity, $itemPrice, $itemCurrency, $itemUrl);
        }

        $data['Items'] = $temp['itemName'];

        $this->maxLength($data, 'InvoiceMark', 1);

        // 檢查電子發票參數
        if ($data['InvoiceMark'] == InvoiceState::YES) {
            // RelateNumber(不可為空)
            $this->required($data, 'RelateNumber');
            $this->maxLength($data, 'RelateNumber', 30);

            if (empty($data['CustomerIdentifier']) === false && strlen($data['CustomerIdentifier']) != 8) {
                throw new InvalidRequestException('CustomerIdentifier length should be 8.');
            }

            $this->maxLength($data, 'CarruerType', 1);
            // 統一編號不為空字串時，載具類別請設定空字串
            if (empty($data['CustomerIdentifier']) === false && $data['CarruerType'] !== CarruerType::NONE) {
                throw new InvalidRequestException('CarruerType should be None.');
            }

            $this->maxLength($data, 'CustomerID', 20);
            // 當載具類別為會員載具(Member)時，此參數不可為空字串
            if ($data['CarruerType'] === CarruerType::MEMBER) {
                $this->required($data, 'CustomerID');
            }

            $this->maxLength($data, 'Donation', 1);
            // 統一編號不為空字串時，請設定不捐贈(No)
            if (empty($data['CustomerIdentifier']) === false && $data['Donation'] != Donation::NO) {
                throw new InvalidRequestException('Donation should be No.');
            } elseif (empty($data['Donation']) === true) {
                $data['Donation'] = Donation::NO;
            }

            $this->maxLength($data, 'Print', 1);
            // 捐贈註記為捐贈(Yes)時，請設定不列印(No)
            if (empty($data['Donation']) === Donation::YES && $data['Print'] != PrintMark::NO) {
                throw new InvalidRequestException('Print should be No.');
            // 統一編號不為空字串時，請設定列印(Yes)
            } elseif (empty($data['CustomerIdentifier']) === false && $data['Print'] !== PrintMark::YES) {
                throw new InvalidRequestException('Print should be Yes.');
            } elseif (empty($data['Print']) === true) {
                $data['Print'] = PrintMark::NO;

                // 載具類別為會員載具(Member)、買受人自然人憑證(Citizen)、買受人手機條碼(Cellphone)時，請設定不列印(No)
                $notPrint = [CarruerType::MEMBER, CarruerType::CITIZEN, CarruerType::CELLPHONE];
                if (in_array($data['CarruerType'], $notPrint) && $data['Print'] == PrintMark::YES) {
                    throw new InvalidRequestException('Print should be No.');
                }
            }

            $this->maxLength($data, 'CustomerName', 20, 'UTF-8');
            // 列印註記為列印(Yes)時，此參數不可為空字串
            if ($data['Print'] == PrintMark::YES) {
                $this->required($data, 'CustomerName');
            }

            $this->maxLength($data, 'CustomerAddr', 200, 'UTF-8');
            // 列印註記為列印(Yes)時，此參數不可為空字串
            if ($data['Print'] == PrintMark::YES) {
                $this->required($data, 'CustomerAddr');
            }

            // CustomerPhone(與CustomerEmail擇一不可為空)
            $this->maxLength($data, 'CustomerPhone', 20);
            $this->maxLength($data, 'CustomerEmail', 200);
            if (empty($data['CustomerPhone']) === true && empty($data['CustomerEmail']) === true) {
                throw new InvalidRequestException('CustomerPhone or CustomerEmail is required.');
            }

            $this->maxLength($data, 'TaxType', 1);
            // TaxType(不可為空)
            $this->required($data, 'TaxType');

            $this->maxLength($data, 'ClearanceMark', 1);
            // 請設定空字串，僅課稅類別為零稅率(Zero)時，此參數不可為空字串
            if ($data['TaxType'] === TaxType::ZERO) {
                if ($data['ClearanceMark'] !== ClearanceMark::YES && $data['ClearanceMark'] !== ClearanceMark::NO) {
                    throw new InvalidRequestException('ClearanceMark is required.');
                }
            } elseif (strlen($data['ClearanceMark']) > 0) {
                throw new InvalidRequestException('Please remove ClearanceMark.');
            }

            $this->maxLength($data, 'CarruerNum', 64);

            switch ($data['CarruerType']) {
                // 載具類別為無載具(None)或會員載具(Member)時，請設定空字串
                case CarruerType::NONE:
                case CarruerType::MEMBER:
                    if (empty($data['CarruerNum']) === false) {
                        throw new InvalidRequestException('Please remove CarruerNum.');
                    }
                    break;
                // 載具類別為買受人自然人憑證(Citizen)時，請設定自然人憑證號碼，前2碼為大小寫英文，後14碼為數字
                case CarruerType::CITIZEN:
                    if (! preg_match('/^[a-zA-Z]{2}\d{14}$/', $data['CarruerNum'])) {
                        throw new InvalidRequestException('Invalid CarruerNum.');
                    }
                    break;
                // 載具類別為買受人手機條碼(Cellphone)時，請設定手機條碼，第1碼為「/」，後7碼為大小寫英文、數字、「+」、「-」或「.」
                case CarruerType::CELLPHONE:
                    if (! preg_match('/^\/{1}[0-9a-zA-Z+-.]{7}$/', $data['CarruerNum'])) {
                        throw new InvalidRequestException('Invalid CarruerNum.');
                    }
                    break;
                default:
                    throw new InvalidRequestException('Please remove CarruerNum.');
                    break;
            }

            // LoveCode(預設為空字串)
            // 捐贈註記為捐贈(Yes)時，參數長度固定3~7碼，請設定全數字或第1碼大小寫「X」，後2~6碼全數字
            if ($data['Donation'] == Donation::YES && ! preg_match('/^([xX]{1}[0-9]{2,6}|[0-9]{3,7})$/', $data['LoveCode'])) {
                throw new InvalidRequestException('Invalid LoveCode.');
            } elseif (empty($data['LoveCode']) === false) {
                throw new InvalidRequestException('Please remove LoveCode.');
            }

            // InvoiceItemName(UrlEncode, 不可為空)
            // InvoiceItemCount(不可為空)
            // InvoiceItemWord(UrlEncode, 不可為空)
            // InvoiceItemPrice(不可為空)
            // InvoiceItemTaxType(不可為空)
            // if (sizeof($data['InvoiceItems']) > 0) {
            //     $tmpItemName = [];
            //     $tmpItemCount = [];
            //     $tmpItemWord = [];
            //     $tmpItemPrice = [];
            //     $tmpItemTaxType = [];
            //     foreach ($data['InvoiceItems'] as $tmpItemInfo) {
            //         if (mb_strlen($tmpItemInfo['Name'], 'UTF-8') > 0) {
            //             array_push($tmpItemName, $tmpItemInfo['Name']);
            //         }
            //         if (strlen($tmpItemInfo['Count']) > 0) {
            //             array_push($tmpItemCount, $tmpItemInfo['Count']);
            //         }
            //         if (mb_strlen($tmpItemInfo['Word'], 'UTF-8') > 0) {
            //             array_push($tmpItemWord, $tmpItemInfo['Word']);
            //         }
            //         if (strlen($tmpItemInfo['Price']) > 0) {
            //             array_push($tmpItemPrice, $tmpItemInfo['Price']);
            //         }
            //         if (strlen($tmpItemInfo['TaxType']) > 0) {
            //             array_push($tmpItemTaxType, $tmpItemInfo['TaxType']);
            //         }
            //     }

            //     if ($data['TaxType'] == TaxType::MIX) {
            //         if (in_array(TaxType::DUTIABLE, $tmpItemTaxType) and in_array(TaxType::FREE, $tmpItemTaxType)) {
            //             // Do nothing
            //         } else {
            //             $tmpItemTaxType = [];
            //         }
            //     }
            //     if ((count($tmpItemName) + count($tmpItemCount) + count($tmpItemWord) + count($tmpItemPrice) + count($tmpItemTaxType)) == (count($tmpItemName) * 5)) {
            //         $szInvoiceItemName = implode($InvSptr, $tmpItemName);
            //         $szInvoiceItemCount = implode($InvSptr, $tmpItemCount);
            //         $szInvoiceItemWord = implode($InvSptr, $tmpItemWord);
            //         $szInvoiceItemPrice = implode($InvSptr, $tmpItemPrice);
            //         $szInvoiceItemTaxType = implode($InvSptr, $tmpItemTaxType);
            //     } else {
            //         throw new InvalidRequestException('Invalid Invoice Goods information.');
            //     }
            // } else {
            //     throw new InvalidRequestException('Invoice Goods information not found.');
            // }

            // DelayDay(不可為空, 預設為0)
            // 延遲天數，範圍0~15，設定為0時，付款完成後立即開立發票
            $data['DelayDay'] = (int) $data['DelayDay'];
            if ($data['DelayDay'] < 0 || $data['DelayDay'] > 15) {
                throw new InvalidRequestException('DelayDay should be 0 ~ 15.');
            } elseif (empty($data['DelayDay']) === true) {
                $data['DelayDay'] = 0;
            }

            // InvType(不可為空)
            $this->required($data, 'InvType');
        }

        // 信用卡特殊邏輯判斷(行動裝置畫面的信用卡分期處理，不支援定期定額)
        if ($data['ChoosePayment'] == PaymentMethod::CREDIT &&
            $data['DeviceSource'] == DeviceType::MOBILE &&
            ! $dataExtend['PeriodAmount']
        ) {
            $data['ChoosePayment'] = PaymentMethod::ALL;
            $data['IgnorePayment'] = 'WebATM#ATM#CVS#BARCODE#Alipay#Tenpay#TopUpUsed#APPBARCODE#AccountLink';
        }

        // 產生畫面控制項與傳遞參數。
        // $arParameters = array(
        //   'MerchantID' => $this->MerchantID,
        //   'PaymentType' => $this->PaymentType,
        //   'ItemName' => $szItemName,
        //   'ItemURL' => $this->Send['ItemURL'],
        //   'InvoiceItemName' => $szInvoiceItemName,
        //   'InvoiceItemCount' => $szInvoiceItemCount,
        //   'InvoiceItemWord' => $szInvoiceItemWord,
        //   'InvoiceItemPrice' => $szInvoiceItemPrice,
        //   'InvoiceItemTaxType' => $szInvoiceItemTaxType,
        // );

        // 處理延伸參數
        if (empty($data['PlatformID']) === true) {
            unset($data['PlatformID']);
        }

        return $data;

        dump($data);

        // switch ($this->getPaymentMethod()) {
        //     case PaymentMethod::ALL:
        //         break;
        // }

        exit;

        $data = [];
        $skipParamerters = [
            'hashKey',
            'hashIV',
            'testMode',
        ];
        $parameters = $this->getParameters();
        $aliases = $this->getAliases();
        foreach ($parameters as $key => $value) {
            if (in_array($key, $skipParamerters, true) === true) {
                continue;
            }

            if (isset($aliases[$key]) === true) {
                if ($key === 'items') {
                    // $value = implode('#', $value);
                    $temp = [];
                    foreach ($value as $item) {
                        $temp[] = $item->getName();
                    }
                    $value = implode('#', $temp);
                }
                $data[$aliases[$key]] = $value;
            } else {
                $data[ucfirst($key)] = $value;
            }
        }

        return $data;
    }

    public function sendData($data)
    {
        // $endPoint = $this->getEndPoint();
        $signature = $this->generateSignature($this->getHashKey(), $this->getHashIV(), $data);
        $data['CheckMacValue'] = $signature;
        $this->response = new Response($this, $data);

        return $this->response;
    }

    public function generateSignature($hashKey, $hashIV, $data)
    {
        # 調整ksort排序規則--依自然排序法(大小寫不敏感)
        ksort($data, SORT_NATURAL | SORT_FLAG_CASE);

        $signature = 'HashKey='.$hashKey;
        foreach ($data as $key => $value) {
            $signature .= '&'.$key.'='.$value;
        }
        $signature .= 'HashIV='.$hashIV;
        $signature = strtolower(urlencode($signature));

        $signature = $this->replaceChars($signature);

        // MD5 編碼
        return md5($signature);
    }

    protected function replaceChars($value)
    {
        // 取代為與 dotNet 相符的字元
        $search_list = ['%2d', '%5f', '%2e', '%21', '%2a', '%28', '%29'];
        $replace_list = ['-', '_', '.', '!', '*', '(', ')'];
        $value = str_replace($search_list, $replace_list, $value);
        // Customize for Magento
        // $value = str_replace('%3f___sid%3d'.session_id(), '', $value);
        // $value = str_replace('%3f___sid%3du', '', $value);
        // $value = str_replace('%3f___sid%3ds', '', $value);

        return $value;
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
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

    public function setClientBackUrl($value)
    {
        return $this->setParameter('clientBackUrl', $value);
    }

    public function getClientBackUrl()
    {
        return $this->getParameter('clientBackUrl');
    }

    public function setOrderResultUrl($value)
    {
        return $this->setParameter('orderResultUrl', $value);
    }

    public function getOrderResultUrl()
    {
        return $this->getParameter('orderResultUrl');
    }

    public function setMerchantTradeDate($value)
    {
        return $this->setParameter('merchantTradeDate', $value);
    }

    public function getMerchantTradeDate()
    {
        return $this->getParameter('merchantTradeDate');
    }

    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    public function setRemark($value)
    {
        return $this->setParameter('remark', $value);
    }

    public function getRemark()
    {
        return $this->getParameter('remark');
    }

    public function setChooseSubPayment($value)
    {
        return $this->setParameter('chooseSubPayment', $value);
    }

    public function getChooseSubPayment()
    {
        return $this->getParameter('chooseSubPayment');
    }

    public function setExtraPaidInfo($value)
    {
        return $this->setParameter('extraPaidInfo', $value);
    }

    public function getExtraPaidInfo()
    {
        return $this->getParameter('extraPaidInfo');
    }

    public function setDeviceType($value)
    {
        return $this->setParameter('deviceType', $value);
    }

    public function getDeviceType()
    {
        return $this->getParameter('deviceType');
    }

    public function setIgnorePayment($value)
    {
        return $this->setParameter('ignorePayment', $value);
    }

    public function getIgnorePayment()
    {
        return $this->getParameter('ignorePayment');
    }

    public function setPlatformId($value)
    {
        return $this->setParameter('platformId', $value);
    }

    public function getPlatformId()
    {
        return $this->getParameter('platformId');
    }

    public function setInvoiceState($value)
    {
        return $this->setParameter('invoiceState', $value);
    }

    public function getInvoiceState()
    {
        return $this->getParameter('invoiceState');
    }

    public function setExpireDate($value)
    {
        return $this->setParameter('expireDate', $value);
    }

    public function getExpireDate()
    {
        return $this->getParameter('expireDate');
    }

    public function setDesc1($value)
    {
        return $this->setParameter('desc1', $value);
    }

    public function getDesc1()
    {
        return $this->getParameter('desc1');
    }

    public function setDesc2($value)
    {
        return $this->setParameter('desc2', $value);
    }

    public function getDesc2()
    {
        return $this->getParameter('desc2');
    }

    public function setDesc3($value)
    {
        return $this->setParameter('desc3', $value);
    }

    public function getDesc3()
    {
        return $this->getParameter('desc3');
    }

    public function setDesc4($value)
    {
        return $this->setParameter('desc4', $value);
    }

    public function getDesc4()
    {
        return $this->getParameter('desc4');
    }

    public function setClientRedirectUrl($value)
    {
        return $this->setParameter('clientRedirectUrl', $value);
    }

    public function getClientRedirectUrl()
    {
        return $this->getParameter('clientRedirectUrl');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setPhoneNo($value)
    {
        return $this->setParameter('phoneNo', $value);
    }

    public function getPhoneNo()
    {
        return $this->getParameter('phoneNo');
    }

    public function setUserName($value)
    {
        return $this->setParameter('userName', $value);
    }

    public function getUserName()
    {
        return $this->getParameter('userName');
    }

    public function setExpireTime($value)
    {
        return $this->setParameter('expireTime', $value);
    }

    public function getExpireTime()
    {
        return $this->getParameter('expireTime');
    }

    public function setCreditInstallment($value)
    {
        return $this->setParameter('creditInstallment', $value);
    }

    public function getCreditInstallment()
    {
        return $this->getParameter('creditInstallment');
    }

    public function setInstallmentAmount($value)
    {
        return $this->setParameter('installmentAmount', $value);
    }

    public function getInstallmentAmount()
    {
        return $this->getParameter('installmentAmount');
    }

    public function setRedeem($value)
    {
        return $this->setParameter('redeem', $value);
    }

    public function getRedeem()
    {
        return $this->getParameter('redeem');
    }

    public function setUnionPay($value)
    {
        return $this->setParameter('unionPay', $value);
    }

    public function getUnionPay()
    {
        return $this->getParameter('unionPay');
    }

    public function setPeriodAmount($value)
    {
        return $this->setParameter('periodAmount', $value);
    }

    public function getPeriodAmount()
    {
        return $this->getParameter('periodAmount');
    }

    public function setPeriodType($value)
    {
        return $this->setParameter('periodType', $value);
    }

    public function getPeriodType()
    {
        return $this->getParameter('periodType');
    }

    public function setFrequency($value)
    {
        return $this->setParameter('frequency', $value);
    }

    public function getFrequency()
    {
        return $this->getParameter('frequency');
    }

    public function setExecTimes($value)
    {
        return $this->setParameter('execTimes', $value);
    }

    public function getExecTimes()
    {
        return $this->getParameter('execTimes');
    }

    public function setPaymentInfoUrl($value)
    {
        return $this->setParameter('paymentInfoUrl', $value);
    }

    public function getPaymentInfoUrl()
    {
        return $this->getParameter('paymentInfoUrl');
    }

    public function setPeriodReturnUrl($value)
    {
        return $this->setParameter('periodReturnUrl', $value);
    }

    public function getPeriodReturnUrl()
    {
        return $this->getParameter('periodReturnUrl');
    }

    public function setCustomerIdentifier($value)
    {
        return $this->setParameter('customerIdentifier', $value);
    }

    public function getCustomerIdentifier()
    {
        return $this->getParameter('customerIdentifier');
    }

    public function setCarruerType($value)
    {
        return $this->setParameter('carruerType', $value);
    }

    public function getCarruerType()
    {
        return $this->getParameter('carruerType');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setDonation($value)
    {
        return $this->setParameter('donation', $value);
    }

    public function getDonation()
    {
        return $this->getParameter('donation');
    }

    public function setPrint($value)
    {
        return $this->setParameter('print', $value);
    }

    public function getPrint()
    {
        return $this->getParameter('print');
    }

    public function setCustomerName($value)
    {
        return $this->setParameter('customerName', $value);
    }

    public function getCustomerName()
    {
        return $this->getParameter('customerName');
    }

    public function setCustomerAddr($value)
    {
        return $this->setParameter('customerAddr', $value);
    }

    public function getCustomerAddr()
    {
        return $this->getParameter('customerAddr');
    }

    public function setCustomerPhone($value)
    {
        return $this->setParameter('customerPhone', $value);
    }

    public function getCustomerPhone()
    {
        return $this->getParameter('customerPhone');
    }

    public function setCustomerEmail($value)
    {
        return $this->setParameter('customerEmail', $value);
    }

    public function getCustomerEmail()
    {
        return $this->getParameter('customerEmail');
    }

    public function setClearanceMark($value)
    {
        return $this->setParameter('clearanceMark', $value);
    }

    public function getClearanceMark()
    {
        return $this->getParameter('clearanceMark');
    }

    public function setCarruerNum($value)
    {
        return $this->setParameter('carruerNum', $value);
    }

    public function getCarruerNum()
    {
        return $this->getParameter('carruerNum');
    }

    public function setLoveCode($value)
    {
        return $this->setParameter('loveCode', $value);
    }

    public function getLoveCode()
    {
        return $this->getParameter('loveCode');
    }

    public function setInvoiceRemark($value)
    {
        return $this->setParameter('invoiceRemark', $value);
    }

    public function getInvoiceRemark()
    {
        return $this->getParameter('invoiceRemark');
    }

    public function setDelayDay($value)
    {
        return $this->setParameter('delayDay', $value);
    }

    public function getDelayDay()
    {
        return $this->getParameter('delayDay');
    }
}
