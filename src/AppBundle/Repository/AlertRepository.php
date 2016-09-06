<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\User;

/**
 * AlertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlertRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function findAllAlertsByUser(User $user){
        $qb = $this->createQueryBuilder('a');

        $qb
            ->where('a.user = :user')
            ->setParameter('user', $user)
        ;

        return $qb->getQuery()->getResult();
    }
}
