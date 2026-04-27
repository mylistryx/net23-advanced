<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use common\enums\IdentityStatus;
use common\fixtures\IdentityFixture;
use common\models\Identity;
use frontend\forms\VerifyEmailForm;
use frontend\tests\UnitTester;
use yii\base\InvalidArgumentException;
use yii\db\Exception;

class VerifyEmailFormTest extends Unit
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

    public function testVerifyWrongToken(): void
    {
        $this->tester->expectThrowable(InvalidArgumentException::class, function (): void {
            new VerifyEmailForm('');
        });

        $this->tester->expectThrowable(InvalidArgumentException::class, function (): void {
            new VerifyEmailForm('notexistingtoken_1391882543');
        });
    }

    public function testAlreadyActivatedToken(): void
    {
        $this->tester->expectThrowable(InvalidArgumentException::class, function (): void {
            new VerifyEmailForm('already_used_token_1548675330');
        });
    }

    /**
     * @throws Exception
     */
    public function testVerifyCorrectToken(): void
    {
        $model = new VerifyEmailForm('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
        $identity = $model->verifyEmail();
        verify($identity)->instanceOf(Identity::class);

        verify($identity->username)->equals('test.test');
        verify($identity->email)->equals('test@mail.com');
        verify($identity->status)->equals(IdentityStatus::Active->value);
        verify($identity->validatePassword('Test1234'))->true();
    }
}
