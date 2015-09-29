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
            'returnUrl' => 'http://www.allpay.com.tw/receive.php',
            'clientBackUrl' => 'http://www.allpay.com.tw/receive.php',
            'orderResultUrl' => '',
            // Alias transactionId
            // 'merchantTradeNo' => '',
            'merchantTradeDate' => date('Y/m/d H:i:s'),
            'paymentType' => 'aio',
            // Alias amount
            // 'totalAmount' => '',
            // Alias description
            // 'tradeDesc' => '',
            'choosePayment' => PaymentMethod::ALL,
            'remark' => '',
            'chooseSubPayment' => PaymentMethodItem::NONE,
            'needExtraPaidInfo' => ExtraPaymentInfo::NO,
            'deviceSource' => DeviceType::PC,
            'ignorePayment' => '',
            'platformID' => '',
            'invoiceMark' => InvoiceState::NO,
        ];

        $sendExtend = [
            // ATM 延伸參數。
            'expireDate' => 3,
            // CVS, BARCODE 延伸參數。
            'desc1' => '',
            'desc2' => '',
            'desc3' => '',
            'desc4' => '',
            // ATM, CVS, BARCODE 延伸參數。
            'clientRedirectUrl' => 'http://www.allpay.com.tw/ClientRedirectURL.php',
            // Alipay 延伸參數。
            'email' => '',
            'phoneNo' => '',
            'userName' => '',
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
            'paymentInfoUrl' => 'http://www.allpay.com.tw/paymentinfo.php',
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
            'delayDay' => 0,
        ];

        return array_merge($send, $sendExtend);
    }

    public function getData()
    {
        $data = array_merge($this->getDefaultParameters(), $this->getParameters());
        $data = Helper::aliases($data);

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
                    if ($data['TaxType'] == TaxType::Zero) {
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
                        case CarruerType::None:
                        case CarruerType::Member:
                            if (strlen($data['CarruerNum']) > 0) {
                                array_push($arErrors, 'Please remove CarruerNum.');
                            }
                            break;
                        // 載具類別為買受人自然人憑證(Citizen)時，請設定自然人憑證號碼，前2碼為大小寫英文，後14碼為數字
                        case CarruerType::Citizen:
                            if (! preg_match('/^[a-zA-Z]{2}\d{14}$/', $data['CarruerNum'])) {
                                array_push($arErrors, 'Invalid CarruerNum.');
                            }
                            break;
                        // 載具類別為買受人手機條碼(Cellphone)時，請設定手機條碼，第1碼為「/」，後7碼為大小寫英文、數字、「+」、「-」或「.」
                        case CarruerType::Cellphone:
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

    public function setClientBackUrl($value)
    {
        return $this->setParameter('clientBackUrl', $value);
    }

    public function getClientBackUrl()
    {
        return $this->getParameter('clientBackUrl');
    }

    public function setDeviceSource($value)
    {
        return $this->setParameter('deviceSource', $value);
    }

    public function getDeviceSource()
    {
        return $this->getParameter('deviceSource');
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

    public function setChoosePayment($value)
    {
        return $this->setParameter('choosePayment', $value);
    }

    public function getChoosePayment()
    {
        return $this->getParameter('choosePayment');
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

    public function setNeedExtraPaidInfo($value)
    {
        return $this->setParameter('needExtraPaidInfo', $value);
    }

    public function getNeedExtraPaidInfo()
    {
        return $this->getParameter('needExtraPaidInfo');
    }

    public function setIgnorePayment($value)
    {
        return $this->setParameter('ignorePayment', $value);
    }

    public function getIgnorePayment()
    {
        return $this->getParameter('ignorePayment');
    }

    public function setPlatformID($value)
    {
        return $this->setParameter('platformID', $value);
    }

    public function getPlatformID()
    {
        return $this->getParameter('platformID');
    }

    public function setInvoiceMark($value)
    {
        return $this->setParameter('invoiceMark', $value);
    }

    public function getInvoiceMark()
    {
        return $this->getParameter('invoiceMark');
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
