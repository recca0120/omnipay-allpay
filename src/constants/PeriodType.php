<?php

namespace Recca0120\AllPay\Constants;

/**
 * 定期定額的週期種類。.
 */
abstract class PeriodType
{
    /**
     * 無
     */
    const NONE = '';

    /**
     * 年.
     */
    const YEAR = 'Y';

    /**
     * 月.
     */
    const MONTH = 'M';

    /**
     * 日.
     */
    const DAY = 'D';
}
