<?php

namespace frontend\tests\functional;

use Codeception\Exception\ModuleException;
use common\enums\IdentityStatus;
use common\fixtures\IdentityFixture;
use common\models\Identity;
use frontend\tests\FunctionalTester;

class ResendVerificationEmailCest
{
    protected string $formId = '#resend-verification-email-form';


    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @return array
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @see \Codeception\Module\Yii2::_before()
     */
    public function _fixtures(): array
    {
        return [
            'identity' => [
                'class'    => IdentityFixture::class,
                'dataFile' => codecept_data_dir() . 'IdentityFixture.php',
            ],
        ];
    }

    public function _before(FunctionalTester $I): void
    {
        $I->amOnRoute('/signup/resend-verification-email');
    }

    protected function formParams($email): array
    {
        return [
            'ResendVerificationEmailForm[email]' => $email,
        ];
    }

    public function checkPage(FunctionalTester $I): void
    {
        $I->see('Resend verification email', 'h1');
        $I->see('Please fill out your email. A verification email will be sent there.');
    }

    public function checkEmptyField(FunctionalTester $I): void
    {
        $I->submitForm($this->formId, $this->formParams(''));
        $I->seeValidationError('Email cannot be blank.');
    }

    public function checkWrongEmailFormat(FunctionalTester $I): void
    {
        $I->submitForm($this->formId, $this->formParams('abcd.com'));
        $I->seeValidationError('Email is not a valid email address.');
    }

    public function checkWrongEmail(FunctionalTester $I): void
    {
        $I->submitForm($this->formId, $this->formParams('wrong@email.com'));
        $I->seeValidationError('There is no user with this email address.');
    }

    public function checkAlreadyVerifiedEmail(FunctionalTester $I): void
    {
        $I->submitForm($this->formId, $this->formParams('test2@mail.com'));
        $I->seeValidationError('There is no user with this email address.');
    }

    /**
     * @throws ModuleException
     */
    public function checkSendSuccessfully(FunctionalTester $I): void
    {
        $I->submitForm($this->formId, $this->formParams('test@mail.com'));
        $I->canSeeEmailIsSent();
        $I->seeRecord(Identity::class, [
            'email'    => 'test@mail.com',
            'username' => 'test.test',
            'status'   => IdentityStatus::Inactive->value,
        ]);
        $I->see('Check your email for further instructions.');
    }
}
