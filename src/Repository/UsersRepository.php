<?php

namespace App\Repository;


use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UsersRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }


    /**
     * @param User $user
     * @param $data
     * @return User
     */
    public function update(User $user, $data)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $user->setValue($key, $value);
            }
        }
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }


}