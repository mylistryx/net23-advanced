<?php

namespace frontend\tests\unit\models;

use Codeception\Exception\ModuleException;
use Codeception\Test\Unit;
use common\fixtures\IdentityFixture;
use frontend\forms\ResendVerificationEmailForm;
use frontend\tests\UnitTester;
use Yii;
use yii\mail\MessageInterface;

class ResendVerificationEmailFormTest extends Unit
{
    protected UnitTester $tester;


    public function _before(): void
    {
        $this->tester->haveFixtures([
            'identity' => [
                'class'    => IdentityFixture::class,
                'dataFile' => codecept_data_dir() . 'IdentityFixture.php',
            ],
        ]);
    }

    public function testWrongEmailAddress(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'aaa@bbb.cc',
        ];

        verify($model->validate())->false();
        verify($model->hasErrors())->true();
        verify($model->getFirstError('email'))->equals('There is no user with this email address.');
    }

    public function testEmptyEmailAddress(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => '',
        ];

        verify($model->validate())->false();
        verify($model->hasErrors())->true();
        verify($model->getFirstError('email'))->equals('Email cannot be blank.');
    }

    public function testResendToActiveUser(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'test2@mail.com',
        ];

        verify($model->validate())->false();
        verify($model->hasErrors())->true();
        verify($model->getFirstError('email'))->equals('There is no user with this email address.');
    }

    /**
     * @throws ModuleException
     */
    public function testSuccessfullyResend(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'test@mail.com',
        ];

        verify($model->validate())->true();
        verify($model->hasErrors())->false();

        verify($model->sendEmail())->true();
        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        verify($mail)->instanceOf(MessageInterface::class);
        verify($mail->getTo())->arrayHasKey('test@mail.com');
        verify($mail->getFrom())->arrayHasKey(Yii::$app->params['supportEmail']);
        verify($mail->getSubject())->equals('Account registration at ' . Yii::$app->name);
        verify($mail->toString())->stringContainsString('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
    }
}
