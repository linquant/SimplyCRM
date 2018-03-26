<?php

namespace AppBundle\Repository;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository
{

    public function listByCustomer($customer)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.customer = :customer')
            ->setParameter('customer', $customer)
            ->orderBy('t.echeance')
            ->getQuery();

        return $qb->execute();


    }

    public function taskDate($customer, $etat)
    {

        if ($etat == 'toutes'){

          $qb = $this->listByCustomer($customer);

        }
        else{


            $qb = $this->createQueryBuilder('t')
                ->where('t.customer = :customer')
                ->andWhere(('t.etat = :etat'))
                ->setParameter('customer', $customer)
                ->setParameter('etat', $etat)
                ->orderBy('t.echeance')
                ->getQuery()->execute();


        }
        return $qb;


    }



    public function countTaskByUser($user)
    {
        $count = $this->createQueryBuilder('t')
            ->select('count(t)')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return $count;


    }

    public function countTaskByUserAndEtat($user, $etats)
    {

        foreach ($etats as $index => $etat) {

            $count[$etat] = $this->createQueryBuilder('t')
                ->select('count(t)')
                ->where('t.user = :user')
                ->andWhere(('t.etat = :etat'))
                ->setParameter('user', $user)
                ->setParameter('etat', $etat)
                ->getQuery()
                ->getSingleScalarResult();


        }



        return $count;

    }
}
