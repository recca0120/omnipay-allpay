<?php

namespace Recca0120\AllPay\Constants;

/**
 * 課稅類別.
 */
abstract class TaxType
{
    // 應稅
    const DUTIABLE = '1';

    // 零稅率
    const ZERO = '2';

    // 免稅
    const FREE = '3';

    // 應稅與免稅混合(限收銀機發票無法分辦時使用，且需通過申請核可)
    const MIX = '9';
}
