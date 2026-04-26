<?php

namespace common\traits;

use Yii;

trait EnumToArrayTrait
{
    public static function getLabel(mixed $field): string
    {
        return self::arrayForDropdown()[$field] ?? $field;
    }

    public static function arrayForDropdown(string $itemsPrefix = '', string $translateSection = 'enum'): array
    {
        $arr = self::array();

        $translateSection .= __CLASS__;

        foreach ($arr as &$name) {
            $name = $itemsPrefix . Yii::t($translateSection, $name);
        }

        return $arr;
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}