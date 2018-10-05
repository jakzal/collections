<?php declare(strict_types=1);

namespace Zalas\Collection\Tests;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Traversable;
use Zalas\Collection\Collection;

abstract class CollectionTestCase extends TestCase
{
    public function test_it_is_a_collection()
    {
        $this->assertInstanceOf(Collection::class, $this->collection(new ArrayIterator()));
    }

    public function test_it_iterates_over_its_elements()
    {
        $elements = [1, 2, 3];

        $c = $this->collection(new ArrayIterator($elements));

        $this->assertIteratesLike($elements, $c);
    }

    public function test_it_merges_two_collections()
    {
        $c1 = $this->collection(new ArrayIterator([1, 2, 3]));
        $c2 = $this->collection(new ArrayIterator([4, 5]));

        $c = $c1->merge($c2);

        $this->assertNotSame($c1, $c, 'merge() creates a new collection');
        $this->assertNotSame($c2, $c, 'merge() creates a new collection');
        $this->assertIteratesLike([1, 2, 3, 4, 5], $c);
    }

    public function test_it_filters_elements_in_the_collection()
    {
        $c = $this->collection(new ArrayIterator([1, 2, 3, 4]));
        $filtered = $c->filter(function (int $e) {
            return 0 === $e % 2;
        });

        $this->assertNotSame($c, $filtered, 'filter() creates a new collection');
        $this->assertIteratesLike([2, 4], $filtered);
    }

    public function test_it_maps_elements_in_the_collection()
    {
        $c = $this->collection(new ArrayIterator([1, 2, 3, 4]));
        $mapped = $c->map(function (int $e) {
            return $e * 2;
        });

        $this->assertNotSame($c, $mapped, 'map() creates a new collection');
        $this->assertIteratesLike([2, 4, 6, 8], $mapped);
    }

    public function test_it_folds_the_collection_left()
    {
        $c = $this->collection(new ArrayIterator(['a', 'b', 'c']));
        $reduced = $c->reduce('d', function (string $a, string $b): string {
            return $a . $b;
        });

        $this->assertSame('dabc', $reduced);
    }

    protected function assertIteratesLike(array $elements, Collection $c)
    {
        $this->assertSame($elements, \iterator_to_array($c));
    }

    protected function assertIteratesSubset(array $elements, Collection $c)
    {
        $this->assertSame($elements, $this->take($c, \count($elements)));
    }

    abstract protected function collection(Traversable $traversable): Collection;

    private function take(Collection $c, int $count): array
    {
        return \iterator_to_array(
            (function () use ($c, $count) {
                foreach ($c as $i => $e) {
                    yield $i => $e;

                    if ($i + 1>= $count) {
                        return;
                    }
                }
            })()
        );
    }
}
