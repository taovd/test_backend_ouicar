<?php

namespace App\Repository;

use App\Entity\CarDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarDay[]    findAll()
 * @method CarDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarDayRepository extends ServiceEntityRepository
{
    /**
     * PriceRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarDay::class);
    }

    // /**
    //  * @return CarDay[] Returns an array of CarDay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('cd')
            ->andWhere('cd.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('cd.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarDay
    {
        return $this->createQueryBuilder('cd')
            ->andWhere('cd.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
