<?php

namespace frontend\forms;

use common\enums\IdentityStatus;
use common\models\Identity;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\db\Exception;

class VerifyEmailForm extends Model
{
    public ?string $token = null;

    private ?Identity $_identity = null;


    public function __construct(string $token, array $config = [])
    {
        $this->_identity = Identity::findByVerificationToken($token);
        if (!$this->_identity) {
            throw new InvalidArgumentException('Wrong verify email token.');
        }
        parent::__construct($config);
    }

    /**
     * @throws Exception
     */
    public function verifyEmail(): ?Identity
    {
        $identity = $this->_identity;
        $identity->status = IdentityStatus::Active->value;
        return $identity->save(false) ? $identity : null;
    }
}
