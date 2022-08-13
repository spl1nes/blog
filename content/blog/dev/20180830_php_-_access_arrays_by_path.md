# PHP - Access Arrays By Path

For dynamically created arrays or for arrays with a high dimension it can be very helpful to access, set or unset values based on a string path. The path can very often generated much easier than the manual array traversing. For this purpose three array functions will are implemented in this article which allow you to access, set or unset values based on a path.

## Requirements

* php 7.0

## Get

The get implementation is probably the most straight forward implementation as you simply have to separate the path into its single components and than follow this path in the array.

### Implementation

```php
public static function getArray(string $path, array $data, string $delim = '/')
{
    $pathParts = \explode($delim, \trim($path, $delim));
    $current   = $data;

    if ($pathParts === false) {
        throw new \Exception();
    }

    foreach ($pathParts as $key) {
        if (!isset($current[$key])) {
            return null;
        }

        $current = $current[$key];
    }

    return $current;
}
```

The path delimiter can be chosen by the user and the return value will be either `null` if the path is incorrect or the value of the path is directing towards.

### Example

```php
$yourArray = [
    'a' => [
        'aa' => 1,
        'ab' => [
            'aba',
            'ab0',
            [
                3,
                'c',
            ],
            4,
        ],
    ],
    2 => '2a',
];

getArray('/a/ab/1', $yourArray, '/'); // returns 'ab0'
```

## Unset

The unset implementation is a little bit more complicated than the get implementation but still fairly straight forward. The basic idea is to iterate through the array by reference as long as the path has not finished. Once at the end of the path the last reference will be unset.

### Implementation

```php
public static function unsetArray(string $path, array $data, string $delim = '/') : array
{
    $nodes  = \explode($delim, \trim($path, $delim));
    $prevEl = null;
    $el     = &$data;
    $node   = null;

    if ($nodes === false) {
        throw new \Exception();
    }

    foreach ($nodes as $node) {
        $prevEl = &$el;

        if (!isset($el[$node])) {
            break;
        }

        $el = &$el[$node];
    }

    if ($prevEl !== null) {
        unset($prevEl[$node]);
    }

    return $data;
}
```

### Example

```php
$yourArray = [
    'a' => [
        'aa' => 1,
        'ab' => [
            'aba',
            'ab0',
        ],
    ],
    2 => '2a',
];

$newArray = unsetArray('/a/ab', $yourArray, '/'); // returns array without /a/ab key & value
```

## Set

Finally we come to the set functionality which no only can add new elements to the array but also modify existing values in an array based on a provided path. Before we start we have to think about some special cases and decide how to handle them.

1. What happens if the key at the end of the path already exists? Should it be overwritten? -> The user should decide.
2. What happens if the value at the end of the path **is** an array and the new value **is not** an array? -> Add value to array.
3. What happens if the value at the end of the path **is** an array and the new value **is** an array? -> Merge arrays.
4. What happens if the value at the end of the path **is not** an array? -> Make the value an array and put both values in it.
5. Everything else? Just set the new value.

### Implementation

```php
public static function setArray(string $path, array $data, $value, string $delim = '/', bool $overwrite = false) : array
{
    $pathParts = \explode($delim, \trim($path, $delim));
    $current   = &$data;

    if ($pathParts === false) {
        throw new \Exception();
    }

    foreach ($pathParts as $key) {
        $current = &$current[$key];
    }

    if ($overwrite) {
        $current = $value;
    } elseif (\is_array($current) && !\is_array($value)) {
        $current[] = $value;
    } elseif (\is_array($current) && \is_array($value)) {
        $current = \array_merge($current, $value);
    } elseif (\is_scalar($current) && $current !== null) {
        $current = [$current, $value];
    } else {
        $current = $value;
    }

    return $data;
}
```

### Example

```php
$newArray = [];
setArray('a/ab', $newArray, 'abb', '/'); // returns ['a' => ['ab' => 'abb']];
```