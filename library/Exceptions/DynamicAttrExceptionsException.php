<?php

declare(strict_types=1);

namespace RespectValidationRules\Exceptions;

use Respect\Validation\Exceptions\NestedValidationException;

class DynamicAttrExceptionsException extends NestedValidationException
{
    public const NOT_PRESENT = 'not_present';
    public const INVALID = 'invalid';

    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::NOT_PRESENT => 'Attribute {{name}} must be present',
            self::INVALID => 'Attribute {{name}} must be valid',
        ],
        self::MODE_NEGATIVE => [
            self::NOT_PRESENT => 'Attribute {{name}} must not be present',
            self::INVALID => 'Attribute {{name}} must not validate',
        ],
    ];
}
