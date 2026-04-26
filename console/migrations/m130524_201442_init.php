<?php

use common\components\migrations\Migration;
use common\enums\IdentityStatus;
use common\enums\Tables;

class m130524_201442_init extends Migration
{
    public function safeUp(): void
    {
        $this->createTable(Tables::Identity->value, [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string()->notNull()->unique(),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token'   => $this->string()->unique()->defaultValue(null),
            'email'                => $this->string()->notNull()->unique(),

            'status'     => $this->smallInteger()->notNull()->defaultValue(IdentityStatus::Inactive->value),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(Tables::Identity->value);
    }
}
