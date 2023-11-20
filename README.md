# respect-validation-rules

## What is this 

This repository will be a set of additional rules for the brilliant data validation library called
[Respect\Validation](https://github.com/Respect/Validation "The most awesome validation engine ever created for PHP")

## Install
### Require php: >= 8.0
    composer require akawalko/respect-validation-rules

## Usage
After installing the package in the place where you want to use my rules, you must use the autoloder and import classes 
from the namespace.

```php
require_once 'vendor/autoload.php';

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Validator as v;
use Respect\Validation\Factory;
```

To use my rules you need to overwrite the default factory so that it knows in which namespace to look for new rules.
```php
Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('RespectValidationRules\\Rules')
        ->withExceptionNamespace('RespectValidationRules\\Exceptions')
);
```

## Rules

### DynamicAttr
When you have an object that uses dynamic properties with the help of magic methods like __get() and __set() 
you can't use a rule **Attribute**. It will not work. The solution is my custom method **DynamicAttr**.

Let's look at this short example:
```php
$dynamicObject = new ClassWithMagicMethods();
$dynamicObject->firstname = 'John Wesley';
$dynamicObject->lastname = 'Connor';
$dynamicObject->age = 36;

$validator = new AllOf(
    v::dynamicAttr(
        'firstname',
        v::stringType()
            ->alpha()
            ->length(2, 10)
    ),

    v::dynamicAttr(
        'lastname',
        v::stringType()
            ->length(null, 190)
        false // property can be undefined
    ),

    v::dynamicAttr(
        'age',
        v::intVal()
            ->min(18)
    )
);

try {
    $validator->assert($dynamicObject);
    echo "valid\n";
} catch (NestedValidationException $exception) {
    echo "not valid\n";
    print_r($exception->getMessages());
}
```

The full code of the example can be found here: 
[examples/example_01.php](https://github.com/akawalko/respect-validation-rules/blob/main/examples/example_01.php)
