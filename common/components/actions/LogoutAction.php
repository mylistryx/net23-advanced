<?php

namespace common\components\actions;

use Yii;
use yii\web\Response;

final class LogoutAction extends BaseAction
{
    public function run(): Response
    {
        Yii::$app->user->logout();

        return $this->controller->goHome();
    }
}