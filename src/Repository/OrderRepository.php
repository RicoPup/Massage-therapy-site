<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param \DateTime $date
     * @param User $user
     * @return array
     */
    public function findByDateAndUser(\DateTime $date, User $user): array
    {
        $startDate = $date->format('Y-m-d') . ' 00:00:00';
        $endDate = $date->format('Y-m-d') . ' 23:59:59';
        return $this->createQueryBuilder('o')
            ->andWhere('o.dateTime >= :startDate')
            ->andWhere('o.dateTime <= :endDate')
            ->andWhere('o.user = :user')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('user', $user)
            ->orderBy('o.dateTime', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param \DateTime $date
     * @return array
     */
    public function findByDate(\DateTime $date): array
    {
        $startDate = $date->format('Y-m-d') . ' 00:00:00';
        $endDate = $date->format('Y-m-d') . ' 23:59:59';
        return $this->createQueryBuilder('o')
            ->andWhere('o.dateTime >= :startDate')
            ->andWhere('o.dateTime <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('o.dateTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
