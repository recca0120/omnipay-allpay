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
use Recca0120\AllPay\Message\Traits\ChoosePaymentAlipay;
use Recca0120\AllPay\Message\Traits\ChoosePaymentAll;
use Recca0120\AllPay\Message\Traits\ChoosePaymentATM;
use Recca0120\AllPay\Message\Traits\ChoosePaymentBarcode;
use Recca0120\AllPay\Message\Traits\ChoosePaymentCredit;
use Recca0120\AllPay\Message\Traits\ChoosePaymentCreditPeriod;
use Recca0120\AllPay\Message\Traits\ChoosePaymentTenpay;
use Recca0120\AllPay\Message\Traits\InvoiceMark;

class AuthorizeRequest extends AbstractRequest
{
    use ChoosePaymentAlipay,
    ChoosePaymentAll,
    ChoosePaymentATM,
    ChoosePaymentBarcode,
    ChoosePaymentCredit,
    ChoosePaymentCreditPeriod,
    ChoosePaymentTenpay,
    InvoiceMark {
        ChoosePaymentATM::setPaymentInfoURL insteadof ChoosePaymentBarcode;
        ChoosePaymentATM::getPaymentInfoURL insteadof ChoosePaymentBarcode;
        ChoosePaymentATM::setClientRedirectURL insteadof ChoosePaymentBarcode;
        ChoosePaymentATM::getClientRedirectURL insteadof ChoosePaymentBarcode;
        ChoosePaymentCredit::setLanguage insteadof ChoosePaymentCreditPeriod;
        ChoosePaymentCredit::getLanguage insteadof ChoosePaymentCreditPeriod;
    }

    public $testEndPoint = [
        DeviceType::PC     => 'https://payment-stage.allpay.com.tw/Cashier/AioCheckOut',
        DeviceType::MOBILE => 'http://payment-stage.allpay.com.tw/Mobile/CreateServerOrder',
    ];

    public $liveEndPoint = [
        DeviceType::PC     => 'https://payment.allpay.com.tw/Cashier/AioCheckOut',
        DeviceType::MOBILE => 'https://payment.allpay.com.tw/Mobile/CreateServerOrder',
    ];

    public function getEndPoint()
    {
        $deviceSource = $this->getDeviceSource();

        return $this->getTestMode() ? $this->testEndPoint[$deviceSource] : $this->liveEndPoint[$deviceSource];
    }

    public function getData()
    {
        $data = Helper::aliases(array_merge([
            'ReturnURL'         => '',
            'ClientBackURL'     => '',
            'OrderResultURL'    => '',
            'MerchantTradeNo'   => '',
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType'       => 'aio',
            'TotalAmount'       => '',
            'TradeDesc'         => '',
            'ChoosePayment'     => PaymentMethod::ALL,
            'Remark'            => '',
            'ChooseSubPayment'  => PaymentMethodItem::NONE,
            'NeedExtraPaidInfo' => ExtraPaymentInfo::NO,
            'DeviceSource'      => DeviceType::PC,
            'IgnorePayment'     => '',
            'PlatformID'        => '',
            'InvoiceMark'       => InvoiceState::NO,

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
            'Email'    => '',
            'PhoneNo'  => '',
            'UserName' => '',
            // Tenpay 延伸參數。
            'ExpireTime' => '',
            // Credit 分期延伸參數。
            'CreditInstallment' => 0,
            'InstallmentAmount' => 0,
            'Redeem'            => false,
            'UnionPay'          => false,
            // Credit 定期定額延伸參數。
            'PeriodAmount' => '',
            'PeriodType'   => '',
            'Frequency'    => '',
            'ExecTimes'    => '',
            // 回傳網址的延伸參數。
            'PaymentInfoURL'  => '',
            'PeriodReturnURL' => '',
            // 電子發票延伸參數。
            'CustomerIdentifier' => '',
            'CarruerType'        => CarruerType::NONE,
            'CustomerID'         => '',
            'Donation'           => Donation::NO,
            'Print'              => PrintMark::NO,
            'CustomerName'       => '',
            'CustomerAddr'       => '',
            'CustomerPhone'      => '',
            'CustomerEmail'      => '',
            'ClearanceMark'      => '',
            'CarruerNum'         => '',
            'LoveCode'           => '',
            'InvoiceRemark'      => '',
            'DelayDay'           => 0,
        ], Helper::skipParameters($this->getParameters())));

        // 變數宣告。
        $arErrors = [];
        $arParameters = null;
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
        if (strlen($this->getMerchantID()) == 0) {
            array_push($arErrors, 'MerchantID is required.');
        }
        if (strlen($this->getMerchantID()) > 10) {
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
        if (count($data['Items']) == 0) {
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
        if (count($data['Items']) > 0) {
            foreach ($data['Items'] as $keys => $value) {
                $szItemName .= vsprintf('#%s %d %s x %u', $data['Items'][$keys]);
                $szAlipayItemName .= sprintf('#%s', $data['Items'][$keys]['Name']);
                $szAlipayItemCounts .= sprintf('#%u', $data['Items'][$keys]['Quantity']);
                $szAlipayItemPrice .= sprintf('#%d', $data['Items'][$keys]['Price']);
                if (!array_key_exists('ItemURL', $data)) {
                    $data['ItemURL'] = $data['Items'][$keys]['URL'];
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
                    // 捐贈註記為捐贈(Yes)時，請設定不列印(No)
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
                    if ($data['Print'] == PrintMark::YES) {
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
                            if (!preg_match('/^[a-zA-Z]{2}\d{14}$/', $data['CarruerNum'])) {
                                array_push($arErrors, 'Invalid CarruerNum.');
                            }
                            break;
                        // 載具類別為買受人手機條碼(Cellphone)時，請設定手機條碼，第1碼為「/」，後7碼為大小寫英文、數字、「+」、「-」或「.」
                        case CarruerType::CELLPHONE:
                            if (!preg_match('/^\/{1}[0-9a-zA-Z+-.]{7}$/', $data['CarruerNum'])) {
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
                    if (!preg_match('/^([xX]{1}[0-9]{2,6}|[0-9]{3,7})$/', $data['LoveCode'])) {
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
                if (count($data['InvoiceItems']) > 0) {
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
        if (count($arErrors) == 0) {
            // 信用卡特殊邏輯判斷(行動裝置畫面的信用卡分期處理，不支援定期定額)
            if ($data['ChoosePayment'] == PaymentMethod::CREDIT && $data['DeviceSource'] == DeviceType::MOBILE && !$data['PeriodAmount']) {
                $data['ChoosePayment'] = PaymentMethod::ALL;
                $data['IgnorePayment'] = 'WebATM#ATM#CVS#BARCODE#Alipay#Tenpay#TopUpUsed#APPBARCODE#AccountLink';
            }
            // 產生畫面控制項與傳遞參數。
            $arParameters = [
                'MerchantID'         => $data['MerchantID'],
                'PaymentType'        => $data['PaymentType'],
                'ItemName'           => $szItemName,
                'ItemURL'            => $data['ItemURL'],
                'InvoiceItemName'    => $szInvoiceItemName,
                'InvoiceItemCount'   => $szInvoiceItemCount,
                'InvoiceItemWord'    => $szInvoiceItemWord,
                'InvoiceItemPrice'   => $szInvoiceItemPrice,
                'InvoiceItemTaxType' => $szInvoiceItemTaxType,
            ];
            $arParameters = array_merge($arParameters, $data);
            // 處理延伸參數
            if (!$data['PlatformID']) {
                unset($arParameters['PlatformID']);
            }
            // 整理全功能參數。
            if ($data['ChoosePayment'] == PaymentMethod::ALL) {
                unset($arParameters['ExecTimes']);
                unset($arParameters['Frequency']);
                unset($arParameters['PeriodAmount']);
                unset($arParameters['PeriodReturnURL']);
                unset($arParameters['PeriodType']);
                $arParameters = array_merge($arParameters, [
                   'AlipayItemName'   => $szAlipayItemName,
                   'AlipayItemCounts' => $szAlipayItemCounts,
                   'AlipayItemPrice'  => $szAlipayItemPrice,
                ]);
                if (!$arParameters['CreditInstallment']) {
                    unset($arParameters['CreditInstallment']);
                }
                if (!$arParameters['InstallmentAmount']) {
                    unset($arParameters['InstallmentAmount']);
                }
                if (!$arParameters['Redeem']) {
                    unset($arParameters['Redeem']);
                }
                if (!$arParameters['UnionPay']) {
                    unset($arParameters['UnionPay']);
                }
                if (!$data['IgnorePayment']) {
                    unset($arParameters['IgnorePayment']);
                }
                if (!$data['ClientRedirectURL']) {
                    unset($arParameters['ClientRedirectURL']);
                }
            }
            // 整理 Alipay 參數。
            if ($data['ChoosePayment'] == PaymentMethod::ALIPAY) {
                $arParameters = array_merge($arParameters, [
                   'AlipayItemName'   => $szAlipayItemName,
                   'AlipayItemCounts' => $szAlipayItemCounts,
                   'AlipayItemPrice'  => $szAlipayItemPrice,
                ]);
                unset($arParameters['CreditInstallment']);
                unset($arParameters['Desc_1']);
                unset($arParameters['Desc_2']);
                unset($arParameters['Desc_3']);
                unset($arParameters['Desc_4']);
                unset($arParameters['ExecTimes']);
                unset($arParameters['ExpireDate']);
                unset($arParameters['ExpireTime']);
                unset($arParameters['Frequency']);
                unset($arParameters['InstallmentAmount']);
                unset($arParameters['PaymentInfoURL']);
                unset($arParameters['PeriodAmount']);
                unset($arParameters['PeriodReturnURL']);
                unset($arParameters['PeriodType']);
                unset($arParameters['Redeem']);
                unset($arParameters['UnionPay']);
                unset($arParameters['IgnorePayment']);
                unset($arParameters['ClientRedirectURL']);
            }
            // 整理 Tenpay 參數。
            if ($data['ChoosePayment'] == PaymentMethod::TENPAY) {
                unset($arParameters['CreditInstallment']);
                unset($arParameters['Desc_1']);
                unset($arParameters['Desc_2']);
                unset($arParameters['Desc_3']);
                unset($arParameters['Desc_4']);
                unset($arParameters['Email']);
                unset($arParameters['ExecTimes']);
                unset($arParameters['ExpireDate']);
                unset($arParameters['Frequency']);
                unset($arParameters['InstallmentAmount']);
                unset($arParameters['PaymentInfoURL']);
                unset($arParameters['PeriodAmount']);
                unset($arParameters['PeriodReturnURL']);
                unset($arParameters['PeriodType']);
                unset($arParameters['PhoneNo']);
                unset($arParameters['Redeem']);
                unset($arParameters['UnionPay']);
                unset($arParameters['UserName']);
                unset($arParameters['IgnorePayment']);
                unset($arParameters['ClientRedirectURL']);
            }
            // 整理 ATM 參數。
            if ($data['ChoosePayment'] == PaymentMethod::ATM) {
                unset($arParameters['CreditInstallment']);
                unset($arParameters['Desc_1']);
                unset($arParameters['Desc_2']);
                unset($arParameters['Desc_3']);
                unset($arParameters['Desc_4']);
                unset($arParameters['Email']);
                unset($arParameters['ExecTimes']);
                unset($arParameters['ExpireTime']);
                unset($arParameters['Frequency']);
                unset($arParameters['InstallmentAmount']);
                unset($arParameters['PeriodAmount']);
                unset($arParameters['PeriodReturnURL']);
                unset($arParameters['PeriodType']);
                unset($arParameters['PhoneNo']);
                unset($arParameters['Redeem']);
                unset($arParameters['UnionPay']);
                unset($arParameters['UserName']);
                unset($arParameters['IgnorePayment']);
                if (!$data['ClientRedirectURL']) {
                    unset($arParameters['ClientRedirectURL']);
                }
            }
            // 整理 BARCODE OR CVS 參數。
            if ($data['ChoosePayment'] == PaymentMethod::BARCODE || $data['ChoosePayment'] == PaymentMethod::CVS) {
                unset($arParameters['CreditInstallment']);
                unset($arParameters['Email']);
                unset($arParameters['ExecTimes']);
                unset($arParameters['ExpireDate']);
                unset($arParameters['ExpireTime']);
                unset($arParameters['Frequency']);
                unset($arParameters['InstallmentAmount']);
                unset($arParameters['PeriodAmount']);
                unset($arParameters['PeriodReturnURL']);
                unset($arParameters['PeriodType']);
                unset($arParameters['PhoneNo']);
                unset($arParameters['Redeem']);
                unset($arParameters['UnionPay']);
                unset($arParameters['UserName']);
                unset($arParameters['IgnorePayment']);
                if (!$data['ClientRedirectURL']) {
                    unset($arParameters['ClientRedirectURL']);
                }
            }
            // 整理全功能、WebATM OR TopUpUsed 參數。
            if ($data['ChoosePayment'] == PaymentMethod::WEBATM || $data['ChoosePayment'] == PaymentMethod::TOPUPUSED) {
                unset($arParameters['CreditInstallment']);
                unset($arParameters['Desc_1']);
                unset($arParameters['Desc_2']);
                unset($arParameters['Desc_3']);
                unset($arParameters['Desc_4']);
                unset($arParameters['Email']);
                unset($arParameters['ExecTimes']);
                unset($arParameters['ExpireDate']);
                unset($arParameters['ExpireTime']);
                unset($arParameters['Frequency']);
                unset($arParameters['InstallmentAmount']);
                unset($arParameters['PaymentInfoURL']);
                unset($arParameters['PeriodAmount']);
                unset($arParameters['PeriodReturnURL']);
                unset($arParameters['PeriodType']);
                unset($arParameters['PhoneNo']);
                unset($arParameters['Redeem']);
                unset($arParameters['UnionPay']);
                unset($arParameters['UserName']);
                unset($arParameters['IgnorePayment']);
                unset($arParameters['ClientRedirectURL']);
            }
            // 整理 Credit 參數。
            if ($data['ChoosePayment'] == PaymentMethod::CREDIT) {
                // Credit 分期。
                $arParameters['Redeem'] = ($arParameters['Redeem'] ? 'Y' : '');
                $arParameters['UnionPay'] = ($arParameters['UnionPay'] ? 1 : 0);
                unset($arParameters['Desc_1']);
                unset($arParameters['Desc_2']);
                unset($arParameters['Desc_3']);
                unset($arParameters['Desc_4']);
                unset($arParameters['Email']);
                unset($arParameters['ExpireDate']);
                unset($arParameters['ExpireTime']);
                unset($arParameters['PaymentInfoURL']);
                unset($arParameters['PhoneNo']);
                unset($arParameters['UserName']);
                unset($arParameters['IgnorePayment']);
                unset($arParameters['ClientRedirectURL']);
            }
            unset($arParameters['Items']);

            // 處理電子發票參數
            unset($arParameters['InvoiceItems']);
            if ($data['InvoiceMark'] == InvoiceState::YES) {
                $encode_fields = [
                   'CustomerName',
                   'CustomerAddr',
                   'CustomerEmail',
                   'InvoiceItemName',
                   'InvoiceItemWord',
                   'InvoiceRemark',
                ];
                foreach ($encode_fields as $tmp_field) {
                    $arParameters[$tmp_field] = urlencode($arParameters[$tmp_field]);
                }
            } else {
                unset($arParameters['InvoiceMark']);
                unset($arParameters['RelateNumber']);
                unset($arParameters['CustomerIdentifier']);
                unset($arParameters['CarruerType']);
                unset($arParameters['CustomerID']);
                unset($arParameters['Donation']);
                unset($arParameters['Print']);
                unset($arParameters['CustomerName']);
                unset($arParameters['CustomerAddr']);
                unset($arParameters['CustomerPhone']);
                unset($arParameters['CustomerEmail']);
                unset($arParameters['TaxType']);
                unset($arParameters['ClearanceMark']);
                unset($arParameters['CarruerNum']);
                unset($arParameters['LoveCode']);
                unset($arParameters['InvoiceItemName']);
                unset($arParameters['InvoiceItemCount']);
                unset($arParameters['InvoiceItemWord']);
                unset($arParameters['InvoiceItemPrice']);
                unset($arParameters['InvoiceItemTaxType']);
                unset($arParameters['InvoiceRemark']);
                unset($arParameters['DelayDay']);
                unset($arParameters['InvType']);
            }
        }
        if (count($arErrors) > 0) {
            throw new InvalidRequestException(implode('- ', $arErrors));
        }

        return $arParameters;
    }
}
