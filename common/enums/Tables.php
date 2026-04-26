<?php

namespace common\enums;

use common\traits\EnumToArrayTrait;

enum Tables: string
{
    use EnumToArrayTrait;

    case Identity = 'identity';
    case IdentityToken = 'identity_access_token';
}