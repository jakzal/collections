<?php declare(strict_types=1);

namespace Zalas\Collection\Tests;

use Traversable;
use Zalas\Collection\Collection;
use Zalas\Collection\LazyCollection;

class LazyCollectionTest extends CollectionTestCase
{
    public function test_it_is_lazy()
    {
        $c = $this->collection((function () {
            yield 1;
            yield 2;
            yield 3;

            throw new \RuntimeException('merge() should be lazy.');
        })());

        $this->assertIteratesSubset([1, 2, 3], $c);
    }

    public function test_merging_collections_is_lazy()
    {
        $c1 = $this->collection((function () {
            yield 1;
            yield 2;
            yield 3;
        })());
        $c2 = $this->collection((function () {
            yield 4;
            yield 5;

            throw new \RuntimeException('merge() should be lazy.');
        })());

        $c = $c1->merge($c2);

        $this->assertIteratesSubset([1, 2, 3, 4, 5], $c);
    }

    public function test_filtering_is_lazy()
    {
        $c = $this->collection((function () {
            yield 1;
            yield 2;
            yield 3;
            yield 4;

            throw new \RuntimeException('filter() should be lazy.');
        })());
        $filtered = $c->filter(function (int $e) {
            return 0 === $e % 2;
        });

        $this->assertIteratesSubset([2, 4], $filtered);
    }

    public function test_mapping_is_lazy()
    {
        $c = $this->collection((function () {
            yield 1;
            yield 2;
            yield 3;
            yield 4;

            throw new \RuntimeException('map() should be lazy.');
        })());
        $mapped = $c->map(function (int $e) {
            return $e * 2;
        });

        $this->assertIteratesSubset([2, 4, 6, 8], $mapped);
    }

    protected function collection(Traversable $traversable): Collection
    {
        return new LazyCollection($traversable);
    }
}
