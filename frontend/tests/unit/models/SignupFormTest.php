<?php

namespace frontend\tests\unit\models;

use Codeception\Exception\ModuleException;
use Codeception\Test\Unit;
use common\enums\IdentityStatus;
use common\fixtures\IdentityFixture;
use common\models\Identity;
use frontend\forms\SignupForm;
use frontend\tests\UnitTester;
use Yii;
use yii\base\Exception;
use yii\mail\MessageInterface;

class SignupFormTest extends Unit
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

    /**
     * @throws \yii\db\Exception
     * @throws Exception
     * @throws ModuleException
     */
    public function testCorrectSignup(): void
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email'    => 'some_email@example.com',
            'password' => 'some_password',
        ]);

        $identity = $model->signup();
        verify($identity)->notEmpty();

        /** @var Identity $identity */
        $identity = $this->tester->grabRecord(Identity::class, [
            'username' => 'some_username',
            'email'    => 'some_email@example.com',
            'status'   => IdentityStatus::Inactive->value,
        ]);

        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        verify($mail)->instanceOf(MessageInterface::class);
        verify($mail->getTo())->arrayHasKey('some_email@example.com');
        verify($mail->getFrom())->arrayHasKey(Yii::$app->params['supportEmail']);
        verify($mail->getSubject())->equals('Account registration at ' . Yii::$app->name);
        verify($mail->toString())->stringContainsString($identity->verification_token);
    }

    /**
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function testNotCorrectSignup(): void
    {
        $model = new SignupForm([
            'username' => 'troy.becker',
            'email'    => 'nicolas.dianna@hotmail.com',
            'password' => 'some_password',
        ]);

        verify($model->signup())->empty();
        verify($model->getErrors('username'))->notEmpty();
        verify($model->getErrors('email'))->notEmpty();

        verify($model->getFirstError('username'))
            ->equals('This username has already been taken.');
        verify($model->getFirstError('email'))
            ->equals('This email address has already been taken.');
    }
}
