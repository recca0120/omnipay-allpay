<?php

namespace Recca0120\AllPay\Constants;

/**
 * 電子發票載具類別.
 */
abstract class CarruerType
{
    // 無載具
    const NONE = '';

    // 會員載具
    const MEMBER = '1';

    // 買受人自然人憑證
    const CITIZEN = '2';

    // 買受人手機條碼
    const CELLPHONE = '3';
}
