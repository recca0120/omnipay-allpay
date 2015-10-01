<?php

namespace Recca0120\AllPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
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
use Recca0120\AllPay\Helper;

class AuthorizeRequest extends AbstractRequest
{
    public $testEndPoint = [
        DeviceType::PC => 'https://payment-stage.allpay.com.tw/Cashier/AioCheckOut',
        DeviceType::MOBILE => 'http://payment-stage.allpay.com.tw/Mobile/CreateServerOrder',
    ];
    public $liveEndPoint = [
        DeviceType::PC => 'https://payment.allpay.com.tw/Cashier/AioCheckOut',
        DeviceType::MOBILE => 'https://payment.allpay.com.tw/Mobile/CreateServerOrder',
    ];

    public function getEndPoint()
    {
        $deviceSource = $this->getDeviceSource();

        return $this->getTestMode() ? $this->testEndPoint[$deviceSource] : $this->liveEndPoint[$deviceSource];
    }

    public function getDefaultParameters()
    {
        $send = [
            'ReturnURL' => '',
            'ClientBackURL' => '',
            'OrderResultURL' => '',
            'MerchantTradeNo' => '',
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => '',
            'TradeDesc' => '',
            'ChoosePayment' => PaymentMethod::ALL,
            'Remark' => '',
            'ChooseSubPayment' => PaymentMethodItem::NONE,
            'NeedExtraPaidInfo' => ExtraPaymentInfo::NO,
            'DeviceSource' => DeviceType::PC,
            'IgnorePayment' => '',
            'PlatformID' => '',
            'InvoiceMark' => InvoiceState::NO,
        ];

        $sendExtend = [
            // ATM 延伸參數。
            'ExpireDate' => 3,
            // CVS, BARCODE 延伸參數。
            'Desc_1' => '',
            'Desc_2' => '',
            'Desc_3' => '',
            'Desc_4' => '',
            // ATM, CVS, BARCODE 延伸參數。
            'ClientRedirectURL' => '',
            // Alipay 延伸參數。
            'Email' => '',
            'PhoneNo' => '',
            'UserName' => '',
            // Tenpay 延伸參數。
            'ExpireTime' => '',
            // Credit 分期延伸參數。
            'CreditInstallment' => 0,
            'InstallmentAmount' => 0,
            'Redeem' => false,
            'UnionPay' => false,
            // Credit 定期定額延伸參數。
            'PeriodAmount' => '',
            'PeriodType' => '',
            'Frequency' => '',
            'ExecTimes' => '',
            // 回傳網址的延伸參數。
            'PaymentInfoURL' => '',
            'PeriodReturnURL' => '',
            // 電子發票延伸參數。
            'CustomerIdentifier' => '',
            'CarruerType' => CarruerType::NONE,
            'CustomerID' => '',
            'Donation' => Donation::NO,
            'Print' => PrintMark::NO,
            'CustomerName' => '',
            'CustomerAddr' => '',
            'CustomerPhone' => '',
            'CustomerEmail' => '',
            'ClearanceMark' => '',
            'CarruerNum' => '',
            'LoveCode' => '',
            'InvoiceRemark' => '',
            'DelayDay' => 0,
        ];

        return array_merge($send, $sendExtend);
    }

    public function getData()
    {
        $data = Helper::aliases(array_merge($this->getDefaultParameters(), $this->getParameters()));

        // 變數宣告。
        $arErrors = [];
        $szHtml = '';

        $szItemName = '';
        $szAlipayItemName = '';
        $szAlipayItemCounts = '';
        $szAlipayItemPrice = '';
        $szInvoiceItemName = '';
        $szInvoiceItemCount = '';
        $szInvoiceItemWord = '';
        $szInvoiceItemPrice = '';
        $szInvoiceItemTaxType = '';
        $InvSptr = '|';

        // 檢查資料。
        if (strlen($this->getHashKey()) == 0) {
            array_push($arErrors, 'HashKey is required.');
        }
        if (strlen($this->getHashIV()) == 0) {
            array_push($arErrors, 'HashIV is required.');
        }
        if (strlen($data['MerchantID']) == 0) {
            array_push($arErrors, 'MerchantID is required.');
        }
        if (strlen($data['MerchantID']) > 10) {
            array_push($arErrors, 'MerchantID max langth as 10.');
        }

        if (strlen($data['ReturnURL']) == 0) {
            array_push($arErrors, 'ReturnURL is required.');
        }
        if (strlen($data['ClientBackURL']) > 200) {
            array_push($arErrors, 'ClientBackURL max langth as 10.');
        }
        if (strlen($data['OrderResultURL']) > 200) {
            array_push($arErrors, 'OrderResultURL max langth as 10.');
        }

        if (strlen($data['MerchantTradeNo']) == 0) {
            array_push($arErrors, 'MerchantTradeNo is required.');
        }
        if (strlen($data['MerchantTradeNo']) > 20) {
            array_push($arErrors, 'MerchantTradeNo max langth as 20.');
        }
        if (strlen($data['MerchantTradeDate']) == 0) {
            array_push($arErrors, 'MerchantTradeDate is required.');
        }
        if (strlen($data['TotalAmount']) == 0) {
            array_push($arErrors, 'TotalAmount is required.');
        }
        if (strlen($data['TradeDesc']) == 0) {
            array_push($arErrors, 'TradeDesc is required.');
        }
        if (strlen($data['TradeDesc']) > 200) {
            array_push($arErrors, 'TradeDesc max langth as 200.');
        }
        if (strlen($data['ChoosePayment']) == 0) {
            array_push($arErrors, 'ChoosePayment is required.');
        }
        if (strlen($data['NeedExtraPaidInfo']) == 0) {
            array_push($arErrors, 'NeedExtraPaidInfo is required.');
        }
        if (strlen($data['DeviceSource']) == 0) {
            array_push($arErrors, 'DeviceSource is required.');
        }
        if (sizeof($data['Items']) == 0) {
            array_push($arErrors, 'Items is required.');
        }

        // 檢查 Alipay 條件。
        if ($data['ChoosePayment'] == PaymentMethod::ALIPAY) {
            if (strlen($data['Email']) == 0) {
                array_push($arErrors, 'Email is required.');
            }
            if (strlen($data['Email']) > 200) {
                array_push($arErrors, 'Email max langth as 200.');
            }
            if (strlen($data['PhoneNo']) == 0) {
                array_push($arErrors, 'PhoneNo is required.');
            }
            if (strlen($data['PhoneNo']) > 20) {
                array_push($arErrors, 'PhoneNo max langth as 20.');
            }
            if (strlen($data['UserName']) == 0) {
                array_push($arErrors, 'UserName is required.');
            }
            if (strlen($data['UserName']) > 20) {
                array_push($arErrors, 'UserName max langth as 20.');
            }
        }
        // 檢查產品名稱。
        if (sizeof($data['Items']) > 0) {
            foreach ($data['Items'] as $item) {
                $szItemName .= vsprintf('#%s %d %s x %u', $item);
                $szAlipayItemName .= sprintf('#%s', $item['Name']);
                $szAlipayItemCounts .= sprintf('#%u', $item['Quantity']);
                $szAlipayItemPrice .= sprintf('#%d', $item['Price']);

                if (! array_key_exists('ItemURL', $data)) {
                    $data['ItemURL'] = $item['URL'];
                }
            }

            if (strlen($szItemName) > 0) {
                $szItemName = mb_substr($szItemName, 1, 200);
            }
            if (strlen($szAlipayItemName) > 0) {
                $szAlipayItemName = mb_substr($szAlipayItemName, 1, 200);
            }
            if (strlen($szAlipayItemCounts) > 0) {
                $szAlipayItemCounts = mb_substr($szAlipayItemCounts, 1, 100);
            }
            if (strlen($szAlipayItemPrice) > 0) {
                $szAlipayItemPrice = mb_substr($szAlipayItemPrice, 1, 20);
            }
        } else {
            array_push($arErrors, 'Goods information not found.');
        }

        // 檢查電子發票參數
        if (strlen($data['InvoiceMark']) > 1) {
            array_push($arErrors, 'InvoiceMark max length as 1.');
        } else {
            if ($data['InvoiceMark'] == InvoiceState::YES) {
                // RelateNumber(不可為空)
                if (strlen($data['RelateNumber']) == 0) {
                    array_push($arErrors, 'RelateNumber is required.');
                } else {
                    if (strlen($data['RelateNumber']) > 30) {
                        array_push($arErrors, 'RelateNumber max length as 30.');
                    }
                }

                // CustomerIdentifier(預設為空字串)
                if (strlen($data['CustomerIdentifier']) > 0) {
                    if (strlen($data['CustomerIdentifier']) != 8) {
                        array_push($arErrors, 'CustomerIdentifier length should be 8.');
                    }
                }

                // CarruerType(預設為None)
                if (strlen($data['CarruerType']) > 1) {
                    array_push($arErrors, 'CarruerType max length as 1.');
                } else {
                    // 統一編號不為空字串時，載具類別請設定空字串
                    if (strlen($data['CustomerIdentifier']) > 0) {
                        if ($data['CarruerType'] != CarruerType::NONE) {
                            array_push($arErrors, 'CarruerType should be None.');
                        }
                    }
                }

                // CustomerID(預設為空字串)
                if (strlen($data['CustomerID']) > 20) {
                    array_push($arErrors, 'CustomerID max length as 20.');
                } else {
                    // 當載具類別為會員載具(Member)時，此參數不可為空字串
                    if ($data['CarruerType'] == CarruerType::MEMBER) {
                        if (strlen($data['CustomerID']) == 0) {
                            array_push($arErrors, 'CustomerID is required.');
                        }
                    }
                }

                // Donation(預設為No)
                if (strlen($data['Donation']) > 1) {
                    array_push($arErrors, 'Donation max length as 1.');
                } else {
                    // 統一編號不為空字串時，請設定不捐贈(No)
                    if (strlen($data['CustomerIdentifier']) > 0) {
                        if ($data['Donation'] != Donation::NO) {
                            array_push($arErrors, 'Donation should be No.');
                        }
                    } else {
                        if (strlen($data['Donation']) == 0) {
                            $data['Donation'] = Donation::NO;
                        }
                    }
                }

                // Print(預設為No)
                if (strlen($data['Print']) > 1) {
                    array_push($arErrors, 'Print max length as 1.');
                } else {
                    // 捐贈註記為捐贈(YES)時，請設定不列印(No)
                    if ($data['Donation'] == Donation::YES) {
                        if ($data['Print'] != PrintMark::NO) {
                            array_push($arErrors, 'Print should be No.');
                        }
                    } else {
                        // 統一編號不為空字串時，請設定列印(Yes)
                        if (strlen($data['CustomerIdentifier']) > 0) {
                            if ($data['Print'] != PrintMark::YES) {
                                array_push($arErrors, 'Print should be Yes.');
                            }
                        } else {
                            if (strlen($data['Print']) == 0) {
                                $data['Print'] = PrintMark::NO;
                            }

                            // 載具類別為會員載具(Member)、買受人自然人憑證(Citizen)、買受人手機條碼(Cellphone)時，請設定不列印(No)
                            $notPrint = [CarruerType::MEMBER, CarruerType::CITIZEN, CarruerType::CELLPHONE];
                            if (in_array($data['CarruerType'], $notPrint) and $data['Print'] == PrintMark::YES) {
                                array_push($arErrors, 'Print should be No.');
                            }
                        }
                    }
                }

                // CustomerName(UrlEncode, 預設為空字串)
                if (mb_strlen($data['CustomerName'], 'UTF-8') > 20) {
                    array_push($arErrors, 'CustomerName max length as 20.');
                } else {
                    // 列印註記為列印(Yes)時，此參數不可為空字串
                    if ($data['Print'] == PrintMark::YES) {
                        if (mb_strlen($data['CustomerName'], 'UTF-8') == 0) {
                            array_push($arErrors, 'CustomerName is required.');
                        }
                    }
                }

                // CustomerAddr(UrlEncode, 預設為空字串)
                if (mb_strlen($data['CustomerAddr'], 'UTF-8') > 200) {
                    array_push($arErrors, 'CustomerAddr max length as 200.');
                } else {
                    // 列印註記為列印(Yes)時，此參數不可為空字串
                    if ($data['Print'] == PrintMark::Yes) {
                        if (mb_strlen($data['CustomerAddr'], 'UTF-8') == 0) {
                            array_push($arErrors, 'CustomerAddr is required.');
                        }
                    }
                }

                // CustomerPhone(與CustomerEmail擇一不可為空)
                if (strlen($data['CustomerPhone']) > 20) {
                    array_push($arErrors, 'CustomerPhone max length as 20.');
                }

                // CustomerEmail(UrlEncode, 預設為空字串, 與CustomerPhone擇一不可為空)
                if (strlen($data['CustomerEmail']) > 200) {
                    array_push($arErrors, 'CustomerEmail max length as 200.');
                }

                if (strlen($data['CustomerPhone']) == 0 and strlen($data['CustomerEmail']) == 0) {
                    array_push($arErrors, 'CustomerPhone or CustomerEmail is required.');
                }

                // TaxType(不可為空)
                if (strlen($data['TaxType']) > 1) {
                    array_push($arErrors, 'TaxType max length as 1.');
                } else {
                    if (strlen($data['TaxType']) == 0) {
                        array_push($arErrors, 'TaxType is required.');
                    }
                }

                // ClearanceMark(預設為空字串)
                if (strlen($data['ClearanceMark']) > 1) {
                    array_push($arErrors, 'ClearanceMark max length as 1.');
                } else {
                    // 請設定空字串，僅課稅類別為零稅率(Zero)時，此參數不可為空字串
                    if ($data['TaxType'] == TaxType::ZERO) {
                        if ($data['ClearanceMark'] != ClearanceMark::YES and $data['ClearanceMark'] != ClearanceMark::NO) {
                            array_push($arErrors, 'ClearanceMark is required.');
                        }
                    } else {
                        if (strlen($data['ClearanceMark']) > 0) {
                            array_push($arErrors, 'Please remove ClearanceMark.');
                        }
                    }
                }

                // CarruerNum(預設為空字串)
                if (strlen($data['CarruerNum']) > 64) {
                    array_push($arErrors, 'CarruerNum max length as 64.');
                } else {
                    switch ($data['CarruerType']) {
                        // 載具類別為無載具(None)或會員載具(Member)時，請設定空字串
                        case CarruerType::NONE:
                        case CarruerType::MEMBER:
                            if (strlen($data['CarruerNum']) > 0) {
                                array_push($arErrors, 'Please remove CarruerNum.');
                            }
                            break;
                        // 載具類別為買受人自然人憑證(Citizen)時，請設定自然人憑證號碼，前2碼為大小寫英文，後14碼為數字
                        case CarruerType::CITIZEN:
                            if (! preg_match('/^[a-zA-Z]{2}\d{14}$/', $data['CarruerNum'])) {
                                array_push($arErrors, 'Invalid CarruerNum.');
                            }
                            break;
                        // 載具類別為買受人手機條碼(Cellphone)時，請設定手機條碼，第1碼為「/」，後7碼為大小寫英文、數字、「+」、「-」或「.」
                        case CarruerType::CELLPHONE:
                            if (! preg_match('/^\/{1}[0-9a-zA-Z+-.]{7}$/', $data['CarruerNum'])) {
                                array_push($arErrors, 'Invalid CarruerNum.');
                            }
                            break;
                        default:
                            array_push($arErrors, 'Please remove CarruerNum.');
                    }
                }

                // LoveCode(預設為空字串)
                // 捐贈註記為捐贈(Yes)時，參數長度固定3~7碼，請設定全數字或第1碼大小寫「X」，後2~6碼全數字
                if ($data['Donation'] == Donation::YES) {
                    if (! preg_match('/^([xX]{1}[0-9]{2,6}|[0-9]{3,7})$/', $data['LoveCode'])) {
                        array_push($arErrors, 'Invalid LoveCode.');
                    }
                } else {
                    if (strlen($data['LoveCode']) > 0) {
                        array_push($arErrors, 'Please remove LoveCode.');
                    }
                }

                // InvoiceItemName(UrlEncode, 不可為空)
                // InvoiceItemCount(不可為空)
                // InvoiceItemWord(UrlEncode, 不可為空)
                // InvoiceItemPrice(不可為空)
                // InvoiceItemTaxType(不可為空)
                if (sizeof($data['InvoiceItems']) > 0) {
                    $tmpItemName = [];
                    $tmpItemCount = [];
                    $tmpItemWord = [];
                    $tmpItemPrice = [];
                    $tmpItemTaxType = [];
                    foreach ($data['InvoiceItems'] as $tmpItemInfo) {
                        if (mb_strlen($tmpItemInfo['Name'], 'UTF-8') > 0) {
                            array_push($tmpItemName, $tmpItemInfo['Name']);
                        }
                        if (strlen($tmpItemInfo['Count']) > 0) {
                            array_push($tmpItemCount, $tmpItemInfo['Count']);
                        }
                        if (mb_strlen($tmpItemInfo['Word'], 'UTF-8') > 0) {
                            array_push($tmpItemWord, $tmpItemInfo['Word']);
                        }
                        if (strlen($tmpItemInfo['Price']) > 0) {
                            array_push($tmpItemPrice, $tmpItemInfo['Price']);
                        }
                        if (strlen($tmpItemInfo['TaxType']) > 0) {
                            array_push($tmpItemTaxType, $tmpItemInfo['TaxType']);
                        }
                    }

                    if ($data['TaxType'] == TaxType::MIX) {
                        if (in_array(TaxType::DUTIABLE, $tmpItemTaxType) and in_array(TaxType::FREE, $tmpItemTaxType)) {
                            // Do nothing
                        } else {
                            $tmpItemTaxType = [];
                        }
                    }
                    if ((count($tmpItemName) + count($tmpItemCount) + count($tmpItemWord) + count($tmpItemPrice) + count($tmpItemTaxType)) == (count($tmpItemName) * 5)) {
                        $szInvoiceItemName = implode($InvSptr, $tmpItemName);
                        $szInvoiceItemCount = implode($InvSptr, $tmpItemCount);
                        $szInvoiceItemWord = implode($InvSptr, $tmpItemWord);
                        $szInvoiceItemPrice = implode($InvSptr, $tmpItemPrice);
                        $szInvoiceItemTaxType = implode($InvSptr, $tmpItemTaxType);
                    } else {
                        array_push($arErrors, 'Invalid Invoice Goods information.');
                    }
                } else {
                    array_push($arErrors, 'Invoice Goods information not found.');
                }

                // InvoiceRemark(UrlEncode, 預設為空字串)

                // DelayDay(不可為空, 預設為0)
                // 延遲天數，範圍0~15，設定為0時，付款完成後立即開立發票
                $data['DelayDay'] = (int) $data['DelayDay'];
                if ($data['DelayDay'] < 0 or $data['DelayDay'] > 15) {
                    array_push($arErrors, 'DelayDay should be 0 ~ 15.');
                } else {
                    if (strlen($data['DelayDay']) == 0) {
                        $data['DelayDay'] = 0;
                    }
                }

                // InvType(不可為空)
                if (strlen($data['InvType']) == 0) {
                    array_push($arErrors, 'InvType is required.');
                }
            }
        }

        // 輸出表單字串。
        if (sizeof($arErrors) == 0) {
            // 信用卡特殊邏輯判斷(行動裝置畫面的信用卡分期處理，不支援定期定額)
            if ($data['ChoosePayment'] == PaymentMethod::CREDIT && $data['DeviceSource'] == DeviceType::MOBILE && ! $dataExtend['PeriodAmount']) {
                $data['ChoosePayment'] = PaymentMethod::ALL;
                $data['IgnorePayment'] = 'WebATM#ATM#CVS#BARCODE#Alipay#Tenpay#TopUpUsed#APPBARCODE#AccountLink';
            }

            $data = array_merge($data, [
                'ItemName' => $szItemName,
                'InvoiceItemName' => $szInvoiceItemName,
                'InvoiceItemCount' => $szInvoiceItemCount,
                'InvoiceItemWord' => $szInvoiceItemWord,
                'InvoiceItemPrice' => $szInvoiceItemPrice,
                'InvoiceItemTaxType' => $szInvoiceItemTaxType,
            ]);
            // 處理延伸參數
            if (! $data['PlatformID']) {
                unset($data['PlatformID']);
            }

            switch ($data['ChoosePayment']) {
                // 整理全功能參數。
                case PaymentMethod::ALL:
                    unset($data['ExecTimes']);
                    unset($data['Frequency']);
                    unset($data['PeriodAmount']);
                    unset($data['PeriodReturnURL']);
                    unset($data['PeriodType']);

                    $data = array_merge($data, [
                        'AlipayItemName' => $szAlipayItemName,
                        'AlipayItemCounts' => $szAlipayItemCounts,
                        'AlipayItemPrice' => $szAlipayItemPrice,
                    ]);

                    if (! $data['CreditInstallment']) {
                        unset($data['CreditInstallment']);
                    }
                    if (! $data['InstallmentAmount']) {
                        unset($data['InstallmentAmount']);
                    }
                    if (! $data['Redeem']) {
                        unset($data['Redeem']);
                    }
                    if (! $data['UnionPay']) {
                        unset($data['UnionPay']);
                    }

                    if (! $data['IgnorePayment']) {
                        unset($data['IgnorePayment']);
                    }
                    if (! $data['ClientRedirectURL']) {
                        unset($data['ClientRedirectURL']);
                    }
                    break;
                // 整理 Alipay 參數。
                case PaymentMethod::ALIPAY:
                    $arParameters = array_merge($arParameters, [
                        'AlipayItemName' => $szAlipayItemName,
                        'AlipayItemCounts' => $szAlipayItemCounts,
                        'AlipayItemPrice' => $szAlipayItemPrice,
                    ]);

                    unset($data['CreditInstallment']);
                    unset($data['Desc_1']);
                    unset($data['Desc_2']);
                    unset($data['Desc_3']);
                    unset($data['Desc_4']);
                    unset($data['ExecTimes']);
                    unset($data['ExpireDate']);
                    unset($data['ExpireTime']);
                    unset($data['Frequency']);
                    unset($data['InstallmentAmount']);
                    unset($data['PaymentInfoURL']);
                    unset($data['PeriodAmount']);
                    unset($data['PeriodReturnURL']);
                    unset($data['PeriodType']);
                    unset($data['Redeem']);
                    unset($data['UnionPay']);

                    unset($data['IgnorePayment']);
                    unset($data['ClientRedirectURL']);
                    break;
                // 整理 Tenpay 參數。
                case PaymentMethod::TENPAY:
                    unset($data['CreditInstallment']);
                    unset($data['Desc_1']);
                    unset($data['Desc_2']);
                    unset($data['Desc_3']);
                    unset($data['Desc_4']);
                    unset($data['Email']);
                    unset($data['ExecTimes']);
                    unset($data['ExpireDate']);
                    unset($data['Frequency']);
                    unset($data['InstallmentAmount']);
                    unset($data['PaymentInfoURL']);
                    unset($data['PeriodAmount']);
                    unset($data['PeriodReturnURL']);
                    unset($data['PeriodType']);
                    unset($data['PhoneNo']);
                    unset($data['Redeem']);
                    unset($data['UnionPay']);
                    unset($data['UserName']);

                    unset($data['IgnorePayment']);
                    unset($data['ClientRedirectURL']);
                    break;
                // 整理 ATM 參數。
                case PaymentMethod::ATM:
                    unset($data['CreditInstallment']);
                    unset($data['Desc_1']);
                    unset($data['Desc_2']);
                    unset($data['Desc_3']);
                    unset($data['Desc_4']);
                    unset($data['Email']);
                    unset($data['ExecTimes']);
                    unset($data['ExpireTime']);
                    unset($data['Frequency']);
                    unset($data['InstallmentAmount']);
                    unset($data['PeriodAmount']);
                    unset($data['PeriodReturnURL']);
                    unset($data['PeriodType']);
                    unset($data['PhoneNo']);
                    unset($data['Redeem']);
                    unset($data['UnionPay']);
                    unset($data['UserName']);

                    unset($data['IgnorePayment']);
                    if (! $data['ClientRedirectURL']) {
                        unset($data['ClientRedirectURL']);
                    }
                    break;
                // 整理 BARCODE OR CVS 參數。
                case PaymentMethod::BARCODE:
                case PaymentMethod::CVS:
                    unset($data['CreditInstallment']);
                    unset($data['Email']);
                    unset($data['ExecTimes']);
                    unset($data['ExpireDate']);
                    unset($data['ExpireTime']);
                    unset($data['Frequency']);
                    unset($data['InstallmentAmount']);
                    unset($data['PeriodAmount']);
                    unset($data['PeriodReturnURL']);
                    unset($data['PeriodType']);
                    unset($data['PhoneNo']);
                    unset($data['Redeem']);
                    unset($data['UnionPay']);
                    unset($data['UserName']);

                    unset($data['IgnorePayment']);
                    if (! $data['ClientRedirectURL']) {
                        unset($data['ClientRedirectURL']);
                    }
                    break;
                // 整理全功能、WebATM OR TopUpUsed 參數。
                case PaymentMethod::WEBATM:
                case PaymentMethod::TOPUPUSED:
                    unset($data['CreditInstallment']);
                    unset($data['Desc_1']);
                    unset($data['Desc_2']);
                    unset($data['Desc_3']);
                    unset($data['Desc_4']);
                    unset($data['Email']);
                    unset($data['ExecTimes']);
                    unset($data['ExpireDate']);
                    unset($data['ExpireTime']);
                    unset($data['Frequency']);
                    unset($data['InstallmentAmount']);
                    unset($data['PaymentInfoURL']);
                    unset($data['PeriodAmount']);
                    unset($data['PeriodReturnURL']);
                    unset($data['PeriodType']);
                    unset($data['PhoneNo']);
                    unset($data['Redeem']);
                    unset($data['UnionPay']);
                    unset($data['UserName']);

                    unset($data['IgnorePayment']);
                    unset($data['ClientRedirectURL']);
                    break;
                 // 整理 Credit 參數。
                case PaymentMethod::CREDIT:
                    // Credit 分期。
                    $data['Redeem'] = ($data['Redeem'] ? 'Y' : '');
                    $data['UnionPay'] = ($data['UnionPay'] ? 1 : 0);

                    unset($data['Desc_1']);
                    unset($data['Desc_2']);
                    unset($data['Desc_3']);
                    unset($data['Desc_4']);
                    unset($data['Email']);
                    unset($data['ExpireDate']);
                    unset($data['ExpireTime']);
                    unset($data['PaymentInfoURL']);
                    unset($data['PhoneNo']);
                    unset($data['UserName']);

                    unset($data['IgnorePayment']);
                    unset($data['ClientRedirectURL']);
                    break;
            }

            unset($data['Items']);

            // 處理電子發票參數
            unset($data['InvoiceItems']);

            if ($data['InvoiceMark'] == InvoiceState::YES) {
                $encodeFields = [
                    'CustomerName',
                    'CustomerAddr',
                    'CustomerEmail',
                    'InvoiceItemName',
                    'InvoiceItemWord',
                    'InvoiceRemark',
                ];
                foreach ($encodeFields as $tmpField) {
                    $data[$tmpField] = urlencode($data[$tmpField]);
                }
            } else {
                unset($data['InvoiceMark']);
                unset($data['RelateNumber']);
                unset($data['CustomerIdentifier']);
                unset($data['CarruerType']);
                unset($data['CustomerID']);
                unset($data['Donation']);
                unset($data['Print']);
                unset($data['CustomerName']);
                unset($data['CustomerAddr']);
                unset($data['CustomerPhone']);
                unset($data['CustomerEmail']);
                unset($data['TaxType']);
                unset($data['ClearanceMark']);
                unset($data['CarruerNum']);
                unset($data['LoveCode']);
                unset($data['InvoiceItemName']);
                unset($data['InvoiceItemCount']);
                unset($data['InvoiceItemWord']);
                unset($data['InvoiceItemPrice']);
                unset($data['InvoiceItemTaxType']);
                unset($data['InvoiceRemark']);
                unset($data['DelayDay']);
                unset($data['InvType']);
            }
        }

        if (sizeof($arErrors) > 0) {
            throw new InvalidRequestException(implode('- ', $arErrors));
        }

        return $data;
    }

    public function sendData($data)
    {
        $this->response = new AuthorizeResponse($this, $data);

        return $this->response;
    }

    public function setAmount($value)
    {
        return $this->setTotalAmount($value);
    }

    public function getAmount()
    {
        return $this->setTotalAmount();
    }

    public function setMerchantTradeDate($value)
    {
        return $this->setParameter('MerchantTradeDate', $value);
    }

    public function getMerchantTradeDate()
    {
        return $this->getParameter('MerchantTradeDate');
    }

    public function setPaymentType($value)
    {
        return $this->setParameter('PaymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('PaymentType');
    }

    public function setTotalAmount($value)
    {
        return $this->setParameter('TotalAmount', $value);
    }

    public function getTotalAmount()
    {
        return $this->getParameter('TotalAmount');
    }

    public function setItemName($value)
    {
        return $this->setParameter('ItemName', $value);
    }

    public function getItemName()
    {
        return $this->getParameter('ItemName');
    }

    public function setChoosePayment($value)
    {
        return $this->setParameter('ChoosePayment', $value);
    }

    public function getChoosePayment()
    {
        return $this->getParameter('ChoosePayment');
    }

    public function setDeviceSource($value)
    {
        return $this->setParameter('DeviceSource', $value);
    }

    public function getDeviceSource()
    {
        return $this->getParameter('DeviceSource');
    }

    public function setClientBackURL($value)
    {
        return $this->setParameter('ClientBackURL', $value);
    }

    public function getClientBackURL()
    {
        return $this->getParameter('ClientBackURL');
    }

    public function setItemURL($value)
    {
        return $this->setParameter('ItemURL', $value);
    }

    public function getItemURL()
    {
        return $this->getParameter('ItemURL');
    }

    public function setRemark($value)
    {
        return $this->setParameter('Remark', $value);
    }

    public function getRemark()
    {
        return $this->getParameter('Remark');
    }

    public function setReturnURL($value)
    {
        return $this->setParameter('ReturnURL', $value);
    }

    public function getReturnURL()
    {
        return $this->getParameter('ReturnURL');
    }

    public function setChooseSubPayment($value)
    {
        return $this->setParameter('ChooseSubPayment', $value);
    }

    public function getChooseSubPayment()
    {
        return $this->getParameter('ChooseSubPayment');
    }

    public function setOrderResultURL($value)
    {
        return $this->setParameter('OrderResultURL', $value);
    }

    public function getOrderResultURL()
    {
        return $this->getParameter('OrderResultURL');
    }

    public function setNeedExtraPaidInfo($value)
    {
        return $this->setParameter('NeedExtraPaidInfo', $value);
    }

    public function getNeedExtraPaidInfo()
    {
        return $this->getParameter('NeedExtraPaidInfo');
    }

    public function setIgnorePayment($value)
    {
        return $this->setParameter('IgnorePayment', $value);
    }

    public function getIgnorePayment()
    {
        return $this->getParameter('IgnorePayment');
    }

    public function setPlatformID($value)
    {
        return $this->setParameter('PlatformID', $value);
    }

    public function getPlatformID()
    {
        return $this->getParameter('PlatformID');
    }

    public function setInvoiceMark($value)
    {
        return $this->setParameter('InvoiceMark', $value);
    }

    public function getInvoiceMark()
    {
        return $this->getParameter('InvoiceMark');
    }

    public function setHoldTradeAMT($value)
    {
        return $this->setParameter('HoldTradeAMT', $value);
    }

    public function getHoldTradeAMT()
    {
        return $this->getParameter('HoldTradeAMT');
    }

    public function setAllPayID($value)
    {
        return $this->setParameter('AllPayID', $value);
    }

    public function getAllPayID()
    {
        return $this->getParameter('AllPayID');
    }

    public function setAccountID($value)
    {
        return $this->setParameter('AccountID', $value);
    }

    public function getAccountID()
    {
        return $this->getParameter('AccountID');
    }

    public function setEncryptType($value)
    {
        return $this->setParameter('EncryptType', $value);
    }

    public function getEncryptType()
    {
        return $this->getParameter('EncryptType');
    }

    public function setExpireDate($value)
    {
        return $this->setParameter('ExpireDate', $value);
    }

    public function getExpireDate()
    {
        return $this->getParameter('ExpireDate');
    }

    public function setPaymentInfoURL($value)
    {
        return $this->setParameter('PaymentInfoURL', $value);
    }

    public function getPaymentInfoURL()
    {
        return $this->getParameter('PaymentInfoURL');
    }

    public function setClientRedirectURL($value)
    {
        return $this->setParameter('ClientRedirectURL', $value);
    }

    public function getClientRedirectURL()
    {
        return $this->getParameter('ClientRedirectURL');
    }

    public function setStoreExpireDate($value)
    {
        return $this->setParameter('StoreExpireDate', $value);
    }

    public function getStoreExpireDate()
    {
        return $this->getParameter('StoreExpireDate');
    }

    public function setDesc1($value)
    {
        return $this->setParameter('Desc_1', $value);
    }

    public function getDesc1()
    {
        return $this->getParameter('Desc_1');
    }

    public function setDesc2($value)
    {
        return $this->setParameter('Desc_2', $value);
    }

    public function getDesc2()
    {
        return $this->getParameter('Desc_2');
    }

    public function setDesc3($value)
    {
        return $this->setParameter('Desc_3', $value);
    }

    public function getDesc3()
    {
        return $this->getParameter('Desc_3');
    }

    public function setDesc4($value)
    {
        return $this->setParameter('Desc_4', $value);
    }

    public function getDesc4()
    {
        return $this->getParameter('Desc_4');
    }

    public function setAlipayItemName($value)
    {
        return $this->setParameter('AlipayItemName', $value);
    }

    public function getAlipayItemName()
    {
        return $this->getParameter('AlipayItemName');
    }

    public function setAlipayItemCounts($value)
    {
        return $this->setParameter('AlipayItemCounts', $value);
    }

    public function getAlipayItemCounts()
    {
        return $this->getParameter('AlipayItemCounts');
    }

    public function setAlipayItemPrice($value)
    {
        return $this->setParameter('AlipayItemPrice', $value);
    }

    public function getAlipayItemPrice()
    {
        return $this->getParameter('AlipayItemPrice');
    }

    public function setEmail($value)
    {
        return $this->setParameter('Email', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('Email');
    }

    public function setPhoneNo($value)
    {
        return $this->setParameter('PhoneNo', $value);
    }

    public function getPhoneNo()
    {
        return $this->getParameter('PhoneNo');
    }

    public function setUserName($value)
    {
        return $this->setParameter('UserName', $value);
    }

    public function getUserName()
    {
        return $this->getParameter('UserName');
    }

    public function setExpireTime($value)
    {
        return $this->setParameter('ExpireTime', $value);
    }

    public function getExpireTime()
    {
        return $this->getParameter('ExpireTime');
    }

    public function setCreditInstallment($value)
    {
        return $this->setParameter('CreditInstallment', $value);
    }

    public function getCreditInstallment()
    {
        return $this->getParameter('CreditInstallment');
    }

    public function setInstallmentAmount($value)
    {
        return $this->setParameter('InstallmentAmount', $value);
    }

    public function getInstallmentAmount()
    {
        return $this->getParameter('InstallmentAmount');
    }

    public function setRedeem($value)
    {
        return $this->setParameter('Redeem', $value);
    }

    public function getRedeem()
    {
        return $this->getParameter('Redeem');
    }

    public function setUnionPay($value)
    {
        return $this->setParameter('UnionPay', $value);
    }

    public function getUnionPay()
    {
        return $this->getParameter('UnionPay');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('Language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('Language');
    }

    public function setPeriodAmount($value)
    {
        return $this->setParameter('PeriodAmount', $value);
    }

    public function getPeriodAmount()
    {
        return $this->getParameter('PeriodAmount');
    }

    public function setPeriodType($value)
    {
        return $this->setParameter('PeriodType', $value);
    }

    public function getPeriodType()
    {
        return $this->getParameter('PeriodType');
    }

    public function setFrequency($value)
    {
        return $this->setParameter('Frequency', $value);
    }

    public function getFrequency()
    {
        return $this->getParameter('Frequency');
    }

    public function setExecTimes($value)
    {
        return $this->setParameter('ExecTimes', $value);
    }

    public function getExecTimes()
    {
        return $this->getParameter('ExecTimes');
    }

    public function setPeriodReturnURL($value)
    {
        return $this->setParameter('PeriodReturnURL', $value);
    }

    public function getPeriodReturnURL()
    {
        return $this->getParameter('PeriodReturnURL');
    }

    public function setRelateNumber($value)
    {
        return $this->setParameter('RelateNumber', $value);
    }

    public function getRelateNumber()
    {
        return $this->getParameter('RelateNumber');
    }

    public function setCustomerID($value)
    {
        return $this->setParameter('CustomerID', $value);
    }

    public function getCustomerID()
    {
        return $this->getParameter('CustomerID');
    }

    public function setCustomerIdentifier($value)
    {
        return $this->setParameter('CustomerIdentifier', $value);
    }

    public function getCustomerIdentifier()
    {
        return $this->getParameter('CustomerIdentifier');
    }

    public function setCustomerName($value)
    {
        return $this->setParameter('CustomerName', $value);
    }

    public function getCustomerName()
    {
        return $this->getParameter('CustomerName');
    }

    public function setCustomerAddr($value)
    {
        return $this->setParameter('CustomerAddr', $value);
    }

    public function getCustomerAddr()
    {
        return $this->getParameter('CustomerAddr');
    }

    public function setCustomerPhone($value)
    {
        return $this->setParameter('CustomerPhone', $value);
    }

    public function getCustomerPhone()
    {
        return $this->getParameter('CustomerPhone');
    }

    public function setCustomerEmail($value)
    {
        return $this->setParameter('CustomerEmail', $value);
    }

    public function getCustomerEmail()
    {
        return $this->getParameter('CustomerEmail');
    }

    public function setClearanceMark($value)
    {
        return $this->setParameter('ClearanceMark', $value);
    }

    public function getClearanceMark()
    {
        return $this->getParameter('ClearanceMark');
    }

    public function setTaxType($value)
    {
        return $this->setParameter('TaxType', $value);
    }

    public function getTaxType()
    {
        return $this->getParameter('TaxType');
    }

    public function setCarruerType($value)
    {
        return $this->setParameter('CarruerType', $value);
    }

    public function getCarruerType()
    {
        return $this->getParameter('CarruerType');
    }

    public function setCarruerNum($value)
    {
        return $this->setParameter('CarruerNum', $value);
    }

    public function getCarruerNum()
    {
        return $this->getParameter('CarruerNum');
    }

    public function setDonation($value)
    {
        return $this->setParameter('Donation', $value);
    }

    public function getDonation()
    {
        return $this->getParameter('Donation');
    }

    public function setLoveCode($value)
    {
        return $this->setParameter('LoveCode', $value);
    }

    public function getLoveCode()
    {
        return $this->getParameter('LoveCode');
    }

    public function setPrint($value)
    {
        return $this->setParameter('Print', $value);
    }

    public function getPrint()
    {
        return $this->getParameter('Print');
    }

    public function setInvoiceItemName($value)
    {
        return $this->setParameter('InvoiceItemName', $value);
    }

    public function getInvoiceItemName()
    {
        return $this->getParameter('InvoiceItemName');
    }

    public function setInvoiceItemCount($value)
    {
        return $this->setParameter('InvoiceItemCount', $value);
    }

    public function getInvoiceItemCount()
    {
        return $this->getParameter('InvoiceItemCount');
    }

    public function setInvoiceItemWord($value)
    {
        return $this->setParameter('InvoiceItemWord', $value);
    }

    public function getInvoiceItemWord()
    {
        return $this->getParameter('InvoiceItemWord');
    }

    public function setInvoiceItemPrice($value)
    {
        return $this->setParameter('InvoiceItemPrice', $value);
    }

    public function getInvoiceItemPrice()
    {
        return $this->getParameter('InvoiceItemPrice');
    }

    public function setInvoiceItemTaxType($value)
    {
        return $this->setParameter('InvoiceItemTaxType', $value);
    }

    public function getInvoiceItemTaxType()
    {
        return $this->getParameter('InvoiceItemTaxType');
    }

    public function setInvoiceRemark($value)
    {
        return $this->setParameter('InvoiceRemark', $value);
    }

    public function getInvoiceRemark()
    {
        return $this->getParameter('InvoiceRemark');
    }

    public function setDelayDay($value)
    {
        return $this->setParameter('DelayDay', $value);
    }

    public function getDelayDay()
    {
        return $this->getParameter('DelayDay');
    }

    public function setInvType($value)
    {
        return $this->setParameter('InvType', $value);
    }

    public function getInvType()
    {
        return $this->getParameter('InvType');
    }
}
