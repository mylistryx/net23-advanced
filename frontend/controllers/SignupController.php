<?php
namespace frontend\controllers;

use common\components\controllers\WebController;
use frontend\forms\ResendVerificationEmailForm;
use frontend\forms\SignupForm;
use frontend\forms\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Response;

final class SignupController extends WebController
{

    public function actionResend(): Response
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->success('Check your email for further instructions.');
                return $this->goHome();
            }
            $this->error('Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resend', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Exception
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->verifyEmail()) {
            $this->success('Your email has been confirmed!');
        } else {
            $this->error('Sorry, we are unable to verify your account with provided token.');
        }

        return $this->goHome();
    }

    public function actionIndex(): Response
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            $this->success('Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}