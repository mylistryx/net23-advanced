<?php

namespace frontend\tests\unit\models;

use Codeception\Exception\ModuleException;
use Codeception\Test\Unit;
use common\fixtures\IdentityFixture;
use common\models\Identity;
use frontend\forms\PasswordResetRequestForm;
use frontend\tests\UnitTester;
use Yii;
use yii\mail\MessageInterface;

class PasswordResetRequestFormTest extends Unit
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

    public function testSendMessageWithWrongEmailAddress(): void
    {
        $model = new PasswordResetRequestForm();
        $model->email = 'not-existing-email@example.com';
        verify($model->sendEmail())->false();
    }

    /**
     * @throws ModuleException
     */
    public function testNotSendEmailsToInactiveUser(): void
    {
        $user = $this->tester->grabFixture('identity', 1);
        $model = new PasswordResetRequestForm();
        $model->email = $user['email'];
        verify($model->sendEmail())->false();
    }

    /**
     * @throws ModuleException
     */
    public function testSendEmailSuccessfully(): void
    {
        $userFixture = $this->tester->grabFixture('identity', 0);

        $model = new PasswordResetRequestForm();
        $model->email = $userFixture['email'];
        $user = Identity::findOne(['password_reset_token' => $userFixture['password_reset_token']]);

        verify($model->sendEmail())->notEmpty();
        verify($user->password_reset_token)->notEmpty();

        $emailMessage = $this->tester->grabLastSentEmail();
        verify($emailMessage)->instanceOf(MessageInterface::class);
        verify($emailMessage->getTo())->arrayHasKey($model->email);
        verify($emailMessage->getFrom())->arrayHasKey(Yii::$app->params['supportEmail']);
    }
}
