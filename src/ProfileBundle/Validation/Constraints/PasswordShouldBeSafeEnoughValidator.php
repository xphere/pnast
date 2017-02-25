<?php

namespace ProfileBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordShouldBeSafeEnoughValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$this->isSafeEnough($value)) {
            $this->context->addViolation($constraint->message);
        }
    }

    private function isSafeEnough(string $value): bool
    {
        return strlen($value) > 3;
    }
}
