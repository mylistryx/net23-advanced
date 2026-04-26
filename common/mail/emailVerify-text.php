<?php

/**
 * @var View $this
 * @var Identity $identity
 */

use common\models\Identity;
use yii\web\View;

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $identity->verification_token]);
?>
Hello <?= $identity->username ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
