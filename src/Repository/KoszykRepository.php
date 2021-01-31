<?php

namespace App\Repository;

use App\Entity\Koszyk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Koszyk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Koszyk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Koszyk[]    findAll()
 * @method Koszyk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KoszykRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Koszyk::class);
    }

    // /**
    //  * @return Koszyk[] Returns an array of Koszyk objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Koszyk
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
