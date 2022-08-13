# PHP - Enum Implementation

Enums are very helpful for managing unique value (strings and numerics). Values can be easily changed without changing the whole code base. Unfortunately enums are not yet implemented in php (not counting the `spl_types` implementation). In this article we will implement basic enum support which has so far fulfilled all of my needs.

## Requirements

* php 7.0

## Implementation

First of all the class we have to implement should be `abstract` as calling the enum implementation by itself has no purpose. The implementation in the actual enumeration should be `final` and `abstract` as you usually don't want to extend a enumeration.

Methods we would like to have are

### isValidValue()

Check if a value is a valid enumeration value. This can be useful in order to check if a value is a valid method parameter

#### Example

```php
function test($value)
{
    if (!TestEnum::isValidValue($value)) {
        return false; // invalid enum value
    }

    // ... continue with normal operations
}
```

### getConstants()

In some cases you would like to get all enum values (e.g. when creating a html select field where all constants represent one valid value).

### getRandom()

Random enum values can be helpful for testing purposes if you would like to test something with random values instead of implementing all potential test values for methods that use enums as parameters.

### getByName()

In some cases enums are used as key / value pair. While for this purpose simple arrays can be used in some cases you need a enum implementation in one place and in another you may want to access the enum value based on its key. For this reason you can use this method to get the value based on the enum name.

### getName()

In revers to the `getByName()` method in some cases you have the value but need to know the key. For this purpose this method is implemented. 

### isValidName()

For the `getByName()` method it's important to check if the name is actually valid. Therefor this method is implemented.

### count()

In some cases it may be important to know the amount of enum values.

### hasFlag()

Very often you want to use bit fields for flags (e.g. permissions read, write, execute) instead of using multiple boolean values. For that purpose you can create an enum implementation where the values use the power of 2.

#### Example

```php
final abstract class TestEnum extends Enum
{
    public const TEST_ENUM1 = 1;
    public const TEST_ENUM2 = 2;
    public const TEST_ENUM3 = 4;
    public const TEST_ENUM4 = 8;
}
```

These values allow to create a bit field:

```php
$bitField = TestEnum::TEST_ENUM1 | TestEnum::TEST_ENUM3;
```

With this function it's easy to check if a bitField has a certain flag set:

```php
TestEnum::hasFlag($bitField, TestEnum::TEST_ENUM3); // returns true
```

### Complete Implementation

```php
abstract class Enum
{
    public static function isValidValue($value) : bool
    {
        $constants = self::getConstants();

        return \in_array($value, $constants, true);
    }

    public static function getConstants() : array
    {
        $reflect = new \ReflectionClass(\get_called_class());

        return $reflect->getConstants();
    }

    public static function getRandom()
    {
        $constants = self::getConstants();
        $keys      = \array_keys($constants);

        return $constants[$keys[\mt_rand(0, \count($constants) - 1)]];
    }

    public static function getByName(string $name)
    {
        if (!self::isValidName($name)) {
            throw new \UnexpectedValueException($name);
        }

        return constant('static::' . $name);
    }

    public static function getName(string $value)
    {
        $arr = self::getConstants();

        return \array_search($value, $arr);
    }

    public static function isValidName(string $name) : bool
    {
        return defined('static::' . $name);
    }

    public static function count() : int
    {
        return \count(self::getConstants());
    }

    public static function hasFlag(int $flags, int $checkForFlag) : bool
    {
        return ($flags & $checkForFlag) === $checkForFlag;
    }
}
```

### Sample Usage

```php
final abstract class TestEnum extends Enum
{
    public const TEST_ENUM1 = 1;
    public const TEST_ENUM2 = 'test';
}
```