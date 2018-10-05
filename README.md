# Collections

A tiny collection library enabling `array_*` like operations on generators and other traversables.

## Example

```php
use Zalas\Collection\LazyCollection;

$c = (new LazyCollection(new \ArrayIterator([1, 2, 3, 4)))
    ->filter(function (int $e) {
        return 0 === $e % 2;
    })
    ->map(function (int $e) {
        return $e * 2;
    })
;
```

## Installation

```
composer require zalas/collections
```
