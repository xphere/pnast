<?php

namespace ProfileBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordShouldBeSafeEnough extends Constraint
{
    public $message = 'Password is not safe enough';
}
