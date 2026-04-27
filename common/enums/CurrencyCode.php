<?php

namespace common\enums;

use common\traits\EnumToArrayTrait;

enum CurrencyCode: string
{
    use EnumToArrayTrait;

    case RUB = 'Российский рубль';
    case BYN = 'Белорусский рубль';
    case KZT = 'Тенге';
    case EUR = 'Евро';
    case USD = 'Доллар США';
    case CNY = 'Юань';
}