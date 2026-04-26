<?php

/**
 * @var View $this
 * @var common\models\Identity $identity
 */

use yii\helpers\Html;
use yii\web\View;

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $identity->verification_token]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($identity->username) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
