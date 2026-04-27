<?php

namespace common\collections;

use common\components\Collection;
use common\enums\IdentityStatus;
use common\models\Identity;

final class IdentityCollection extends Collection
{
    protected ?string $allowedType = Identity::class;

    public function filterActive(): self
    {
        return new self(array_filter($this->all(), fn(Identity $item) => $item->status === IdentityStatus::Active->value));
    }

    public function filterInactive(): self
    {
        return new self(array_filter($this->all(), fn(Identity $item) => $item->status === IdentityStatus::Inactive->value));
    }

    public function filterDeleted(): self
    {
        return new self(array_filter($this->all(), fn(Identity $item) => $item->status === IdentityStatus::Deleted->value));
    }
}