<?php

namespace common\components\behaviors;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeZone;
use yii\behaviors\TimestampBehavior;

class DateTimeBehavior extends TimestampBehavior
{
    /**
     * @throws DateMalformedStringException
     */
    protected function getValue($event): string
    {
        return new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'))->format('Y-m-d H:i:s');
    }
}