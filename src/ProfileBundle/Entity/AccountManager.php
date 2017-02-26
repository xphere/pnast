<?php

namespace ProfileBundle\Entity;

use ProfileBundle\Form\AccountRegistration;
use ProfileBundle\Security\PasswordEncoder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AccountManager
{
    private $accounts;
    private $passwordEncoder;
    private $dispatcher;

    public function __construct(
        AccountRepository $accounts,
        PasswordEncoder $passwordEncoder,
        EventDispatcherInterface $dispatcher
    ) {
        $this->accounts = $accounts;
        $this->passwordEncoder = $passwordEncoder;
        $this->dispatcher = $dispatcher;
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

        $this->raiseEvent(new AccountWasRegistered(
            $account->id(),
            $account->email(),
            $account->name()
        ));

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

    private function raiseEvent(Event $event)
    {
        $this->dispatcher->dispatch(get_class($event), $event);
    }
}
