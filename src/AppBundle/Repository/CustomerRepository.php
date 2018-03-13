<?php

namespace AppBundle\Repository;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerRepository extends \Doctrine\ORM\EntityRepository
{


            public function nbreClient()
            {
                $count = $this->createQueryBuilder('c')
                    ->select('COUNT(c)')
                    ->getQuery()
                    ->getSingleScalarResult();

              ;
                return $count;

            }


    public function pagination($limit = 10, $offset)
        {
            // automatically knows to select Products
            // the "p" is an alias you'll use in the rest of the query
            $qb = $this->createQueryBuilder('c')
                ->setFirstResult( $offset )
                ->setMaxResults( $limit )
                ->getQuery();

            return $qb->execute();

            // to get just one result:
            // $product = $qb->setMaxResults(1)->getOneOrNullResult();
        }



}
