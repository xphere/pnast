<?php

namespace ProfileBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailShouldBeUnique extends Constraint
{
    public $message = 'Email "%email%" is already registered in the system';
}
