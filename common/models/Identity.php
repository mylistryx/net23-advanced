<?php

namespace common\models;

use common\components\db\ActiveRecord;
use common\enums\IdentityStatus;
use common\enums\Tables;
use common\traits\IdentityTrait;
use Override;
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
    use IdentityTrait;

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
}
