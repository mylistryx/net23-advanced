<?php

namespace common\models;

use common\components\db\ActiveRecord;
use common\enums\IdentityStatus;
use common\enums\Tables;
use Override;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * Identity model
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $password write-only password
 */
class Identity extends ActiveRecord implements IdentityInterface
{
    #[Override]
    public false|string $createdAtAttribute = 'created_at';
    #[Override]
    public false|string $updatedAtAttribute = 'updated_at';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return Tables::Identity->value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => IdentityStatus::Inactive->value],
            ['status', 'in', 'range' => IdentityStatus::values()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?static
    {
        return static::findOne([
            'id'     => $id,
            'status' => IdentityStatus::Active->value,
        ]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): ?static
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername(string $username): ?static
    {
        return static::findOne([
            'username' => $username,
            'status'   => IdentityStatus::Active->value,
        ]);
    }

    public static function findByPasswordResetToken(string $token): ?static
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => IdentityStatus::Active->value,
        ]);
    }

    public static function findByVerificationToken(string $token): ?static
    {
        return static::findOne([
            'verification_token' => $token,
            'status'             => IdentityStatus::Inactive->value,
        ]);
    }

    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    public function setPassword(?string $password = null): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }
}
