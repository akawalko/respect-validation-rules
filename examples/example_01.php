<?php
require_once 'vendor/autoload.php';

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Validator as v;
use Respect\Validation\Factory;

$dynamicObject = new class {
    protected array $data;

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }
};

$dynamicObject->firstname = 'John Wesley';
$dynamicObject->lastname = 'Connor';
$dynamicObject->age = 36;

Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('RespectValidationRules\\Rules')
        ->withExceptionNamespace('RespectValidationRules\\Exceptions')
);

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
            ->setName('Lastname'),
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
    echo "Not valid\n";
    print_r($exception->getMessages());
}
