<?php

namespace common\components;

use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use RectorPrefix202604\Illuminate\Contracts\Support\Jsonable;
use Traversable;
use UnitEnum;
use WeakMap;
use yii\base\Arrayable;
use yii\base\InvalidArgumentException;

class Collection implements ArrayAccess
{
    protected ?string $allowedType = null;
    protected array $items = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    protected function newInstance($items = []): static
    {
        return new static($items);
    }

    public function add($item): void
    {
        $this->offsetSet(null, $item);
    }

    public function set($offset, $value): void
    {
        $this->offsetSet($offset, $value);
    }

    public function get($key, $default = null): mixed
    {
        return $this->offsetGet($key) ?? $default;
    }

    public function first(): mixed
    {
        return array_first($this->items);
    }

    public function last(): mixed
    {
        return array_last($this->items);
    }

    public function getOrPut($key, $value): mixed
    {
        if (array_key_exists($key ?? '', $this->items)) {
            return $this->items[$key];
        }

        $this->offsetSet($key, $value);

        return $value;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function exists($offset): bool
    {
        return $this->offsetExists($offset);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public static function range($from, $to, $step = 1, ...$args): static
    {
        return new static(range($from, $to, $step), ...$args);
    }

    public function remove($offset): void
    {
        $this->offsetUnset($offset);
    }

    public function map(callable $callback): static
    {
        return $this->newInstance(array_map($callback, $this->items));
    }

    public function merge($items): static
    {
        return $this->newInstance(array_merge($this->items, $items));
    }

    public function mergeRecursive($items): static
    {
        return $this->newInstance(array_merge_recursive($this->items, $items));
    }

    public function combine($values): static
    {
        return $this->newInstance(array_combine($this->all(), $values));
    }

    public function all(): array
    {
        return $this->items;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->checkTypeCapability($value);

        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    protected function checkTypeCapability($item): void
    {
        if ($this->allowedType !== null && !($item instanceof $this->allowedType)) {
            throw new InvalidArgumentException('Item must be of type ' . $this->allowedType . '.' . gettype($item) . 'given.');
        }
    }
}