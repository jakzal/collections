<?php declare(strict_types=1);

namespace Zalas\Collection;

use Closure;
use Traversable;

final class LazyCollection implements Collection
{
    private $elements;

    public function __construct(Traversable $elements)
    {
        $this->elements = $elements;
    }

    public function getIterator(): Traversable
    {
        yield from $this->elements;
    }

    public function merge(Collection $other): Collection
    {
        return self::createFromClosure(function () use ($other) {
            foreach ($this->elements as $e) {
                yield $e;
            }
            foreach ($other->elements as $e) {
                yield $e;
            }
        });
    }

    public function filter(callable $f): Collection
    {
        return self::createFromClosure(function () use ($f) {
            foreach ($this->elements as $e) {
                if ($f($e)) {
                    yield $e;
                }
            }
        });
    }

    public function map(callable $f): Collection
    {
        return self::createFromClosure(function () use ($f) {
            foreach ($this->elements as $e) {
                yield $f($e);
            }
        });
    }

    /**
     * @param mixed $initial
     * @param callable $f
     *
     * @return mixed
     */
    public function reduce($initial, callable $f)
    {
        return \array_reduce(\iterator_to_array($this->elements), $f, $initial);
    }

    private static function createFromClosure(Closure $f): Collection
    {
        return new self($f());
    }
}
