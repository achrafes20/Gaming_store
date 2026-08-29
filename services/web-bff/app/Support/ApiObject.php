<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Wraps a decoded JSON API response so Blade views can keep using
 * Eloquent-style `$item->field` access — including for fields the response
 * doesn't include, which must resolve to null instead of throwing
 * (unlike a plain stdClass in PHP 8.2+). See docs/architecture.md.
 */
class ApiObject implements \ArrayAccess, \Countable, \IteratorAggregate, Arrayable
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function wrap(mixed $value, ?string $key = null): mixed
    {
        // Laravel convention: any "*_at" field (created_at, expires_at, emitted_at...)
        // is a timestamp. Eloquent auto-casts these to Carbon; a plain decoded JSON
        // response doesn't, so Blade views ported from the monolith that call
        // ->format()/->diffForHumans() on them would otherwise fail on a string.
        if (is_string($value) && $key !== null && str_ends_with($key, '_at')) {
            try {
                return Carbon::parse($value);
            } catch (\Exception) {
                return $value;
            }
        }

        if (is_array($value)) {
            // A list (e.g. "product_photos": [...]) becomes a Collection, not a plain
            // array, so Blade views ported from the monolith can keep calling
            // ->count(), ->first(), etc. exactly like they did on Eloquent relations.
            return array_is_list($value)
                ? collect(array_map([self::class, 'wrap'], $value))
                : new self($value);
        }

        return $value;
    }

    public function __get(string $name): mixed
    {
        return self::wrap($this->data[$name] ?? null, $name);
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * Lets callers attach data the API response didn't include — e.g. web-bff
     * enriching an order line with product info fetched from catalog-service.
     */
    public function __set(string $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }

    public function toArray(): array
    {
        $unwrap = function ($v) use (&$unwrap) {
            if ($v instanceof self) {
                return $v->toArray();
            }
            if ($v instanceof Collection) {
                return $v->map($unwrap)->all();
            }

            return $v;
        };

        return array_map($unwrap, $this->data);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return self::wrap($this->data[$offset] ?? null, is_string($offset) ? $offset : null);
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
            yield $key => self::wrap($value, is_string($key) ? $key : null);
        }
    }

    public function count(): int
    {
        return count($this->data);
    }
}
