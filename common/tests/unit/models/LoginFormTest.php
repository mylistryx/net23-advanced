<?php

namespace common\tests\unit\models;

use Codeception\Test\Unit;
use common\fixtures\IdentityFixture;
use common\forms\LoginForm;
use common\tests\UnitTester;
use Yii;

/**
 * Login form test
 */
class LoginFormTest extends Unit
{
    protected UnitTester $tester;


    /**
     * @return array
     */
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => IdentityFixture::class,
                'dataFile' => codecept_data_dir() . 'IdentityFixture.php'
            ]
        ];
    }

    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        verify($model->login())->false();
        verify(Yii::$app->user->isGuest)->true();
    }

    public function testLoginWrongPassword()
    {
        $model = new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'wrong_password',
        ]);

        verify($model->login())->false();
        verify($model->errors)->arrayHasKey('password');
        verify(Yii::$app->user->isGuest)->true();
    }

    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]);

        verify($model->login())->true();
        verify($model->errors)->arrayHasNotKey('password');
        verify(Yii::$app->user->isGuest)->false();
    }
}
