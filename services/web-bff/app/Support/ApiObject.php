<?php

namespace App\Support;

/**
 * Wraps a decoded JSON API response so Blade views can keep using
 * Eloquent-style `$item->field` access — including for fields the response
 * doesn't include, which must resolve to null instead of throwing
 * (unlike a plain stdClass in PHP 8.2+). See docs/architecture.md.
 */
class ApiObject implements \ArrayAccess, \IteratorAggregate, \Countable, \Illuminate\Contracts\Support\Arrayable
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function wrap(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_is_list($value)
                ? array_map([self::class, 'wrap'], $value)
                : new self($value);
        }

        return $value;
    }

    public function __get(string $name): mixed
    {
        return self::wrap($this->data[$name] ?? null);
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function toArray(): array
    {
        return array_map(
            fn ($v) => $v instanceof self ? $v->toArray() : (is_array($v) ? array_map(fn ($i) => $i instanceof self ? $i->toArray() : $i, $v) : $v),
            $this->data
        );
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return self::wrap($this->data[$offset] ?? null);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function getIterator(): \Iterator
    {
        foreach ($this->data as $key => $value) {
            yield $key => self::wrap($value);
        }
    }

    public function count(): int
    {
        return count($this->data);
    }
}
