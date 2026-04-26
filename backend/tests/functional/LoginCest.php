<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\IdentityFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => IdentityFixture::class,
                'dataFile' => codecept_data_dir() . 'LoginDataFixture.php'
            ]
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I): void
    {
        $I->amOnRoute('/site/login');
        $I->fillField('Username', 'erau');
        $I->fillField('Password', 'password_0');
        $I->click('login-button');

        $I->seeLink('Logout');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }
}
