<?php

namespace frontend\controllers;

use common\components\controllers\WebController;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Response;

final class PasswordController extends WebController
{

    public function actionIndex(): Response
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->success('Check your email for further instructions.');

                return $this->goHome();
            }

            $this->error('Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionReset(string $token): Response
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $this->success('New password saved.');

            return $this->goHome();
        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }
}