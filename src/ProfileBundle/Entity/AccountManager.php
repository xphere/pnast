<?php

namespace ProfileBundle\Entity;

use ProfileBundle\Form\AccountRegistration;
use ProfileBundle\Security\PasswordEncoder;

class AccountManager
{
    private $accounts;
    private $passwordEncoder;

    public function __construct(AccountRepository $accounts, PasswordEncoder $passwordEncoder)
    {
        $this->accounts = $accounts;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function register(AccountRegistration $registration): Account
    {
        $salt = $this->generateRandomSalt();
        $encodedPassword = $this->encodePassword($registration->password, $salt);

        $account = new Account(
            $registration->name,
            $registration->email,
            $encodedPassword,
            $salt
        );

        $this->accounts->save($account);

        return $account;
    }

    public function find(int $id): Account
    {
        return $this->accounts->byId($id);
    }

    private function generateRandomSalt(): string
    {
        return $this->passwordEncoder->generateRandomSalt();
    }

    private function encodePassword(string $plainPassword, string $salt): string
    {
        return $this->passwordEncoder->encode(Account::class, $plainPassword, $salt);
    }
}
