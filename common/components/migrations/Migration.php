<?php

namespace common\components\migrations;

use yii\db\ColumnSchemaBuilder;

class Migration extends \yii\db\Migration
{
    public function uuid(): ColumnSchemaBuilder
    {
        return $this->string(36);
    }

    public function uuidPK(): ColumnSchemaBuilder
    {
        return $this->string(36)->notNull()->unique()->append('PRIMARY KEY');
    }
}