<?php

namespace Recca0120\AllPay\Constants;

/**
 * 電子發票開立註記。.
 */
abstract class InvoiceState
{
    /**
     * 需要開立電子發票。.
     */
    const YES = 'Y';

    /**
     * 不需要開立電子發票。.
     */
    const NO = '';
}
