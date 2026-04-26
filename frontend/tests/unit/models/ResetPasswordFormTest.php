<?php

namespace frontend\tests\unit\models;

use Codeception\Exception\ModuleException;
use Codeception\Test\Unit;
use common\fixtures\IdentityFixture;
use frontend\forms\ResetPasswordForm;
use frontend\tests\UnitTester;
use yii\base\Exception;
use yii\base\InvalidArgumentException;

class ResetPasswordFormTest extends Unit
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

    public function testResetWrongToken(): void
    {
        $this->tester->expectThrowable(InvalidArgumentException::class, function () {
            new ResetPasswordForm('');
        });

        $this->tester->expectThrowable(InvalidArgumentException::class, function () {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws ModuleException
     */
    public function testResetCorrectToken(): void
    {
        $identity = $this->tester->grabFixture('identity', 0);
        $form = new ResetPasswordForm($identity['password_reset_token']);
        verify($form->resetPassword())->notEmpty();
    }
}
