<?php namespace PhAnsi; 

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

final class Collection implements IteratorAggregate, Countable, ArrayAccess
{
    public function __construct(
        private array $items = []
    ) {
    }

    public function contains($value): bool
    {
        return in_array($value, $this->items);
    }

    public function containsMatch(callable $predicate): bool
    {
        $foundItem = $this->first($predicate);

        return ! is_null($foundItem);
    }

    public function add($item): self
    {
        $items = $this->items;
        $items[] = $item;
        return new self($items);
    }

    public function each(callable $predicate): void
    {
        foreach ($this->items as $i) {
            $predicate($i);
        }
    }

    public function index(int $index): mixed
    {
        if ( ! isset($this->items[$index])) {
            return null;
        }

        return $this->items[$index];
    }

    public function indexFor(mixed $valueToFind): ?int
    {
        foreach ($this->items as $key => $value) {
            if ($value === $valueToFind) {
                return $key;
            }
        }

        return null;
    }

    public function equals(Collection $that, callable $predicate = null): bool
    {
        if (is_null($predicate)) {
            return get_class($this) === get_class($that) && $this->items === $that->items;
        }

        // always unequal with different counts
        if ($this->count() != $that->count()) {
            return false;
        }

        $one = $this->toArray();
        $two = $that->toArray();

        foreach (range(0, $this->count() - 1) as $i) {
            if ( ! $predicate($one[0], $two[0])) {
                return false;
            }
        }

        return true;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        return $this->copy()->items;
    }

    public function copy(): self
    {
        return clone $this;
    }

    public function map(callable $f): self
    {
        return new self(array_map($f, $this->items));
    }

    public function mapKeyValues(callable $f): self
    {
        return new self(array_map($f, array_keys($this->items), $this->items));
    }

    public function flatten(): self
    {
        return new self(array_merge(...array_map(fn($x) => $x, $this->items)));
    }

    public function flatMap(callable $f): self
    {
        return new self(array_merge(...array_map($f, $this->items)));
    }

    public function reduce(callable $f, $initial = null)
    {
        return array_reduce($this->items, $f, $initial);
    }

    public function filter(?callable $predicate = null): self
    {
        return is_null($predicate)
            ? new self(array_values(array_filter($this->items)))
            : new self(array_values(array_filter($this->items, $predicate)));
    }

    public function firstIndex(callable $predicate): ?int
    {
        foreach ($this->items as $index => $item) {
            if ($predicate($item)) {
                return $index;
            }
        }
        return null;
    }

    public function first(callable $predicate)
    {
        foreach ($this->items as $item) {
            if ($predicate($item)) {
                return $item;
            }
        }
        return null;
    }

    public function head()
    {
        $value = reset($this->items);

        if (false === $value) {
            return null;
        }
        return $value;
    }

    public function tail(): self
    {
        return new self(array_slice($this->items, 1));
    }

    public function reverse(): self
    {
        return new self(array_reverse($this->items));
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function sort(?callable $f): self
    {
        $items = $this->items;
        usort($items, $f);
        return self::of($items);
    }

    /**
     * Concatenates string items with a delimiter.
     *
     * @param string $delimiter
     * @return string
     */
    public function implode(string $delimiter = ', '): string
    {
        return implode($delimiter, $this->items);
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be cast to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Offset to set
     * This is not supported due to immutable nature.
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new InvalidArgumentException();
    }
    
    public function merge(Collection $that): self
    {
        if (get_class($this) !== get_class($that)) {
            throw new InvalidArgumentException();
        }
        
        return new self(array_merge($this->items, $that->items));
    }
    
    /**
     * Offset to unset
     * This is not supported due to immutable nature.
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new InvalidArgumentException();
    }

    public static function of(array $items): self
    {
        return new self($items);
    }

    public static function empty(): self
    {
        return new self;
    }

    public static function list(...$items): self
    {
        return new self($items);
    }
}
