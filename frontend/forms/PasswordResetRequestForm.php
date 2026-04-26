<?php

namespace frontend\forms;

use common\enums\IdentityStatus;
use common\models\Identity;
use Yii;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public ?string $email = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => Identity::class,
                'filter'      => ['status' => IdentityStatus::Active->value],
                'message'     => 'There is no user with this email address.',
            ],
        ];
    }

    public function sendEmail(): bool
    {
        /* @var $identity Identity */
        $identity = Identity::findOne([
            'status' => IdentityStatus::Active->value,
            'email'  => $this->email,
        ]);

        if (!$identity) {
            return false;
        }

        if (!Identity::isPasswordResetTokenValid($identity->password_reset_token)) {
            $identity->generatePasswordResetToken();
            if (!$identity->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                [
                    'html' => 'passwordResetToken-html',
                    'text' => 'passwordResetToken-text',
                ],
                ['identity' => $identity],
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
