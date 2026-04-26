<?php

namespace common\components\user;

use common\models\Identity;
use yii\web\User;

/**
 * @property Identity $identity
 */
class WebUser extends User
{
    public $enableAutoLogin = true;

    public $identityClass = Identity::class;
}