# 100% Code Coverage! Really?

Achieving 100% code coverage is admirable (not discussing effectiveness and efficiency). However, are you sure you really have 100% code coverage? In this article I will show some simple examples which explain why 100% code coverage != 100% coverage.

Some of the examples are language specific but can probably be transferred to other languages as well.

## If Statements

It's very common to have multiple condition checks in a if statement tested with `&&` or `||`. A problem arises when you use `||` checks and perform additional operations in that or check like assigning values or calling functions.

```php
if ($trueValue || functionCallReturnsBool(...)) {
    ...
}
```

If you design a test where `$trueValue` is true the code coverage will say that that you covered 100% of your code since the if statement is executed BUT since the value `$trueValue` is `true` the secondary condition `functionCallReturnsBool(...)` is never invoked. This becomes especially painful if the function call `functionCallReturnsBool(...)` has side-effects.

Re-writing the above mentioned example shows that despite the coverage report two test cases are required.

```php
if ($trueValue)) {
    ...
} elseif(functionCallReturnsBool(...)) {
    ...
}
```

Alternatively, you may want to enable branch covering in order to avoid this pitfall.

Another solution for this could be to simply make all code executions with side effects (assignments included) before the if statement (many code styles even have this as guideline).
```php
$funcCallResult = functionCallReturnsBool(...);

if ($trueValue || $funcCallResult) {
    ...
}
```

Now you can be sure that the function call at least gets executed (if you want that, note that in some cases you don't even want to execute the or condition if the first condition fails). Nevertheless you still need at least 4 test cases for a true 100% coverge.

* `$trueValue = true; $funcCallResult = true`
* `$trueValue = true; $funcCallResult = false`
* `$trueValue = false; $funcCallResult = true`
* `$trueValue = false; $funcCallResult = false`

## Shorthand/Ternary Operations

Similar to the if statement, shorthand/ternary operations also suffer from the fact that they are written in one line.

```php
$value = $someValue === $someOtherValue ? 1 : 2;
```

No matter what the result of `===` is the code coverage report will tell you that you covered 100% of your code which is not the case. If you would reqrite the code to the following code you would clearly see that you would have to write 2 test cases in order to cover the same code 100%.

```php
if ($someValue === $someOtherValue) {
    $value = 1;
} else {
    $value = 2;
}
```

## Values

A very important factor of our tests is how we choose our input values. Very often we only think about the most common use cases but forget some special cases. 

### String

A simple string example with multibyte characters:

```php
return strlen($string);
```

If we test for a string like `abc` we will get a length of `3` which is expected. 100% code coverage; done!

What happens if we test for multibyte characters like `åèä`? Suddenly the code above returns `6` which is probably not what we expected and our test would fail. Even if our code coverage would have stated that we covered 100% of our code we didn't really test every possible scenario as shown in the previous example.

### Int 

The following example is another very simple demonstration how much can go wrong in a non-happy execution path.

```php
return $a / $b;
```

You probably can already see the problem I'm going to mention. While I can easily write a test for the happy path and make this test pass and achieve 100% coverage I could also write a test for the unhappy path and cause an exception `$a / 0`.

Another example could be to cause over-/underflow

```php
return $a * $b
```

Simply multiply the max value for `$a` (e.g. `PHP_INT_MAX`) with 2 and you will receive a result which you "didn't expect".

### Array Boundaries

Dynamic array indices are another problem, they can cause bugs/exceptions.

```php
return $array[$dynamicIndex];
```
Sometimes the evaluation of the array index can be tricky and not always all possible `$dynamicIndex` get tested.

### Float

Float value operation in general are problematic due to their imperfect precision. Some code may not consider these precision problems and if you don't think about them during testing you'll not catch them. Even if you consider them it's not always obvious how to construct a test which may cause a failure.

```php
return 100-3.26-$a == 96.74-$a;
```

If you test this `$a = 0` or `$a = 20` you will receive true as result but once you test it with `$a = 40` the return will be false due to the internal representation/calculation of floats.

## Types

PHP allows more and more type hints but there are still many situation even in other programming languages where you can have dynamic types and your tests don't test for all possible cases. Sometimes you only expect `numeric` values but for some reason a value might also be of a different type.

## Execution Order and Workflow Dependencies

Immutable objects are one way to ensure which part of a program is responsible for creating a object but applications usually have code sections which depend on each other. In my tests for example I could have a `DatabaseConnection` object and a `DataMapper`. The `DataMapper` depends on a correct setup of the database connection, while the database connection relies maybe on a settings manager for importing the connection settings for the database.

Testing for the happy path where everything is setup is no problem but one must not forget that not everything goes smooth in this setup process and one of the steps in between may fail. 

## Dependencies & Environment

Additionally, software and applications rely more and more on third party libraries, frameworks etc. Depending on how extensively your using third party code your 100% code coverage may only represent a very small amount of the actual code getting executed (library, framework, apache/nginx, mysql/postrges, frontend/backend, browser, php/node, OS ...). Many of the dependencies can even have different settings which can have different effects on your application which most likely are not all tested despite your code coverage stating it is running 100% of your code.

## Conclusion

When writing tests you are most likely thinking about the happy path first (which is not a bad thing, if your happy path doesn't work then you're already in trouble) but try to think of the unhappy path as well. This approach is also often used in science/R&D where you try to break/disprove something in order to show that your implementation is actually correct/good. One solution could be to let programmers write tests which haven't written the code and let them try to break it (in a sensible way). The problem with this is that it can cause problems in the team and requires good communication and training how to communicate the test results.

Tests should be predictable but at the same time I recommend to add some random factor to them. Instead of only running fixed values maybe add random input values so you get at least a chance to discover some bugs you haven't thought about (just remember to make the error report very verbose so it's easy to see which input values causes this problem). In some cases it may even be possible to run a test based on all possible input values. If you have a enum as possible input variable, just loop over all enum values.

With this article I neither want to enforce the need to achieve 100% code coverage nor do I want to condemn it. I simply hope to give you some examples which show that 100% code coverage is not the actual goal you should aim for and doesn't give you a definitive indication of how robust your code is.
