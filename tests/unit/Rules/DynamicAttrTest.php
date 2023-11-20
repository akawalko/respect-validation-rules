<?php

declare(strict_types=1);

namespace RespectValidationRules\Rules;

use Respect\Validation\Validatable;
use RespectValidationRules\Test\RuleTestCase;

final class DynamicAttrTest extends RuleTestCase
{
    public const PROPERTY_VALUE = 'foo';

    public function providerForValidInput(): array
    {
        $obj = $this->makeObjectWithDynamicAttributes();
        $obj->bar = 'foo';

        $extraValidator = $this->createMock(Validatable::class);
        $extraValidator->method('validate')->willReturn(true);

        return [
            'dynamic attribute is present without extra validator' => [new DynamicAttr('bar'), $obj],
            'dynamic attribute is present with extra validator' => [new DynamicAttr('bar', $extraValidator), $obj],
            'non mandatory dynamic attribute is not present' => [
                new DynamicAttr('foo', null, false), $obj
            ],
            'non mandatory dynamic attribute is not present with extra validator' => [
                new DynamicAttr('foo', $extraValidator, false),
                $obj,
            ],
        ];
    }

    public function providerForInvalidInput(): array
    {
        $obj = $this->makeObjectWithDynamicAttributes();
        $obj->bar = 'foo';

        $extraValidatorMock = $this->createMock(Validatable::class);
        $extraValidatorMock->method('validate')->willReturn(false);

        return [
            'dynamic attribute is absent without extra validator' => [new DynamicAttr('barr'), $obj],
            'value provided is an empty string' => [new DynamicAttr('barr'), ''],
            'validator related to dynamic attribute does not validate' => [new DynamicAttr('bar', $extraValidatorMock), $obj],
        ];
    }

    public function makeObjectWithDynamicAttributes()
    {
        return new class {
            private array $data = [];

            public function __get(string $name)
            {
                if (!$this->__isset($name)) {
                    return;
                }
                return $this->data[$name];
            }

            public function __set(string $name, $value): void
            {
                $this->data[$name] = $value;
            }

            public function __isset(string $name): bool
            {
                return isset($this->data[$name]);
            }
        };
    }
}
