<?php

namespace ProfileBundle\Entity;

use Symfony\Component\EventDispatcher\Event;

class AccountWasRegistered extends Event
{
    public $accountId;
    public $email;
    public $name;

    public function __construct(int $accountId, string $email, string $name)
    {
        $this->accountId = $accountId;
        $this->email = $email;
        $this->name = $name;
    }
}
