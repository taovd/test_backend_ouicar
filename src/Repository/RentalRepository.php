<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Rental;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    // /**
    //  * @return Rental[] Returns an array of Rental objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rental
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Car    $car
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function findCarAvailability(Car $car, string $startDate, string $endDate)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.car = :car')
            ->setParameter('car', $car)
            ->andWhere(
                '(r.startDate <= :startDate AND r.endDate >= :startDate)
                OR (r.startDate <= :endDate AND r.endDate >= :endDate)
                '
            )
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ;
//        dump($qb->getQuery()->getSQL());
//        dump($qb->getQuery()->getParameters());
//        die;
        return $qb->getQuery()->getResult();
    }
}
