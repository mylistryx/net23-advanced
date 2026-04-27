<?php

/**
 * @var View $this
 * @var Identity $identity
 */

use common\models\Identity;
use yii\web\View;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['password/reset', 'token' => $identity->password_reset_token]);
?>
Hello <?= $identity->username ?>,

Follow the link below to reset your password: <?= $resetLink ?>
