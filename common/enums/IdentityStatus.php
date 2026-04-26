<?php

namespace common\enums;

use common\traits\EnumToArrayTrait;

enum IdentityStatus: int
{
    use EnumToArrayTrait;

    case Active = 100;
    case Inactive = 10;
    case Deleted = 0;
}