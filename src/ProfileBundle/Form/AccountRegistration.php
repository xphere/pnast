<?php

namespace ProfileBundle\Form;

use ProfileBundle\Validation\Constraints\PasswordShouldBeSafeEnough;
use Symfony\Component\Validator\Constraints as Assert;

class AccountRegistration
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @PasswordShouldBeSafeEnough()
     */
    public $password;
}
