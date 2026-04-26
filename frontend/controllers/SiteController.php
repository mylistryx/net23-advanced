<?php

namespace frontend\controllers;

use common\components\controllers\WebController;
use common\forms\LoginForm;
use frontend\forms\ContactForm;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResendVerificationEmailForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use frontend\forms\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends WebController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error'   => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class'           => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(): Response
    {
        return $this->render('index');
    }

    public function actionLogin(): Response
    {
        if (!$this->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load($this->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact(): Response
    {
        $model = new ContactForm();
        if ($model->load($this->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                $this->success('Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                $this->error('There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout(): Response
    {
        return $this->render('about');
    }

    public function actionSignup(): Response
    {
        $model = new SignupForm();
        if ($model->load($this->post()) && $model->signup()) {
            $this->success('Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset(): Response
    {
        $model = new PasswordResetRequestForm();
        if ($model->load($this->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->success('Check your email for further instructions.');

                return $this->goHome();
            }

            $this->error('Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token): Response
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load($this->post()) && $model->validate() && $model->resetPassword()) {
            $this->success('New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token): Response
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

    public function actionResendVerificationEmail(): Response
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load($this->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->success('Check your email for further instructions.');
                return $this->goHome();
            }
            $this->error('Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model,
        ]);
    }
}
