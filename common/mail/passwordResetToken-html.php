<?php

/**
 * @var View $this
 * @var Identity $identity
 */

use common\models\Identity;
use yii\helpers\Html;
use yii\web\View;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $identity->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($identity->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
