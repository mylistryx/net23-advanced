<?php

namespace frontend\forms;

use common\enums\IdentityStatus;
use common\models\Identity;
use Yii;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
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
                'filter'      => ['status' => IdentityStatus::Inactive->value],
                'message'     => 'There is no user with this email address.',
            ],
        ];
    }

    public function sendEmail(): bool
    {
        $identity = Identity::findOne([
            'email'  => $this->email,
            'status' => IdentityStatus::Inactive->value,
        ]);

        if ($identity === null) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['identity' => $identity],
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
