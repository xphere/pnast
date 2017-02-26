<?php

namespace ProfileBundle\Security;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordEncoder
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function generateRandomSalt(): string
    {
        return base64_encode(random_bytes(30));
    }

    public function encode(string $userClass, string $plainPassword, string $salt): string
    {
        return $this->encoderFactory
            ->getEncoder($userClass)
            ->encodePassword($plainPassword, $salt)
        ;
    }
}
