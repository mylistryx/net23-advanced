<?php

namespace common\components;

abstract class Collection
{
    private array $items = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(object $item): void
    {
        $this->items[] = $item;
    }

    public function all(): array
    {
        return $this->items;
    }
}