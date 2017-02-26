<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class AccountRepository extends EntityRepository
{
    public function byId(int $id): Account
    {
        $result = $this->find($id);
        if ($result instanceof Account) {
            return $result;
        }

        throw new EntityNotFoundException(sprintf(
            'Entity "%s" with id "%d" not found',
            Account::class,
            $id
        ));
    }

    public function isEmailRegistered(string $email): bool
    {
        return $this->findOneBy(['email' => $email]) !== null;
    }

    public function save(Account $account): void
    {
        $em = $this->getEntityManager();
        $em->persist($account);
        $em->flush($account);
    }
}
