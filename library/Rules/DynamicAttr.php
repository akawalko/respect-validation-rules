<?php

declare(strict_types=1);

namespace RespectValidationRules\Rules;

use Respect\Validation\Rules\AbstractRelated;

class DynamicAttr extends AbstractRelated
{
    public function hasReference($input): bool
    {
        $reference = (string) $this->getReference();

        return isset($input->{$reference});
    }

    public function getReferenceValue($input)
    {
        $reference = (string) $this->getReference();

        return $input->{$reference};
    }
}
