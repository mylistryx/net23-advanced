<?php

namespace common\components\db;

use common\components\behaviors\DateTimeBehavior;
use common\components\behaviors\UuidBehavior;
use Exception;
use Yii;
use yii\db\ActiveRecord as BaseActiveRecord;

/**
 * @inheritDoc
 */
abstract class ActiveRecord extends BaseActiveRecord
{
    public bool $useUuidInsteadInt = false;
    public string|false $createdAtAttribute = false;
    public string|false $updatedAtAttribute = false;
    public string|false $isActiveAttribute = false;

    /**
     * @return array
     * @throws Exception
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        if ($this->useUuidInsteadInt) {
            $behaviors['Uuid'] = [
                'class' => UuidBehavior::class,
            ];
        }

        if ($this->createdAtAttribute || $this->updatedAtAttribute) {
            $behaviors['DateTime'] = [
                'class'              => DateTimeBehavior::class,
                'createdAtAttribute' => $this->createdAtAttribute,
                'updatedAtAttribute' => $this->updatedAtAttribute,
            ];
        }

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        if ($this->isActiveAttribute) {
            $rules[] = [$this->isActiveAttribute, 'required'];
            $rules[] = [$this->isActiveAttribute, 'boolean'];
        }
        return $rules;
    }

    public function attributeLabels(): array
    {
        $labels = parent::attributeLabels();

        if ($this->createdAtAttribute) {
            $labels[$this->createdAtAttribute] = Yii::t('app', 'Created at');
        }
        if ($this->updatedAtAttribute) {
            $labels[$this->updatedAtAttribute] = Yii::t('app', 'Updated at');
        }
        if ($this->isActiveAttribute) {
            $labels[$this->isActiveAttribute] = Yii::t('app', 'Is active');
        }

        return $labels;
    }

    /**
     * @param string|null $isActiveAttribute
     * @return bool
     * @throws Exception
     */
    public function toggle(?string $isActiveAttribute = null): bool
    {
        if ($isActiveAttribute && $this->hasAttribute($isActiveAttribute)) {
            $currentStatus = $this->getAttribute($isActiveAttribute);
            $this->setAttribute($isActiveAttribute, !$currentStatus);
            return ($this->isAttributeChanged($isActiveAttribute) && $this->save());
        }

        return false;
    }
}