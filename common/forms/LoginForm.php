<?php

namespace common\forms;

use common\components\forms\Form;
use common\models\Identity;
use Yii;

class LoginForm extends Form
{
    public ?string $username = null;
    public ?string $password = null;
    public bool $rememberMe = true;

    private ?Identity $_identity = null {
        get {
            if ($this->_identity === null) {
                $this->_identity = Identity::findByUsername($this->username);
            }

            return $this->_identity;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword(string $attribute, ?array $params = null): void
    {
        if (!$this->hasErrors()) {
            $user = $this->_identity;
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->_identity, $this->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
        }

        return false;
    }

}
