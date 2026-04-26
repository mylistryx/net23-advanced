<?php

namespace common\components\behaviors;

use Ramsey\Uuid\Uuid;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class UuidBehavior extends AttributeBehavior
{
    public string $pkAttribute = 'id';

    public function init(): void
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_INIT => [$this->pkAttribute],
            ];
        }
    }

    protected function getValue($event): string
    {
        return Uuid::uuid7()->toString();
    }
}