# Collections

[![Build Status](https://travis-ci.com/jakzal/collections.svg?branch=master)](https://travis-ci.com/jakzal/collections)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jakzal/collections/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jakzal/collections/?branch=master)

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
