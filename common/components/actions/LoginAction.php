<?php

namespace common\components\actions;

use common\forms\LoginForm;
use Yii;
use yii\web\Response;

final class LoginAction extends BaseAction
{
    public function run(): Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->controller->goBack();
        }

        $model->password = '';

        return $this->controller->render('login', [
            'model' => $model,
        ]);
    }
}