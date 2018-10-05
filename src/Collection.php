<?php declare(strict_types=1);

namespace Zalas\Collection;

use IteratorAggregate;

interface Collection extends IteratorAggregate
{
    public function merge(Collection $other): Collection;

    public function filter(callable $f): Collection;

    public function map(callable $f): Collection;

    /**
     * @param mixed $initial
     * @param callable $f
     *
     * @return mixed
     */
    public function reduce($initial, callable $f);
}
