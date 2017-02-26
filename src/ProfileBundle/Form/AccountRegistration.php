<?php

namespace ProfileBundle\Form;

use ProfileBundle\Validation\Constraints\EmailShouldBeUnique;
use ProfileBundle\Validation\Constraints\PasswordShouldBeSafeEnough;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"AccountRegistration", "Strict"})
 */
class AccountRegistration
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @EmailShouldBeUnique(groups={"Strict"})
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
