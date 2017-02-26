<?php

namespace ProfileBundle\Validation\Constraints;

use ProfileBundle\Entity\AccountRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailShouldBeUniqueValidator extends ConstraintValidator
{
    private $accounts;

    public function __construct(AccountRepository $accounts)
    {
        $this->accounts = $accounts;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($this->isEmailRegistered($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%email%', $value)
                ->addViolation();
        }
    }

    private function isEmailRegistered(string $email): bool
    {
        return $this->accounts->isEmailRegistered($email);
    }
}
