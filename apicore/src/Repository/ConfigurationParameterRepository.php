<?php

namespace App\Repository;

use App\Entity\ConfigurationParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigurationParameter>
 */
class ConfigurationParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigurationParameter::class);
    }

    public function countConfigurationParameter():int
    {
        $res = $this->createQueryBuilder('p')
            ->select('count(p.id) as c')
            //->where('p.read = true')
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $res;
    }

    public function findByFilter($filter, $orderArray, $limit, $offset)
    {
        $qb = $this->createQueryBuilder('c');

        if ($filter) {
            $qb->andWhere($qb->expr()->like('LOWER(c.name)', ':filter'))
                ->setParameter('filter', '%' . strtolower($filter) . '%');
        }

        foreach ($orderArray as $field => $order) {
            $qb->addOrderBy('c.' . $field, $order);
        }

        return $qb->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countByFilter($filter)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)');

        if ($filter) {
            $qb->andWhere($qb->expr()->like('LOWER(c.name)', ':filter'))
                ->setParameter('filter', '%' . strtolower($filter) . '%');
        }

        return $qb->getQuery()->getSingleScalarResult();
    }



    //    /**
    //     * @return ConfigurationParameter[] Returns an array of ConfigurationParameter objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ConfigurationParameter
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}