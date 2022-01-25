<?php

namespace App\Repository;

use App\Entity\QueueMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QueueMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method QueueMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method QueueMessage[]    findAll()
 * @method QueueMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueueMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QueueMessage::class);
    }

    // /**
    //  * @return QueueMessage[] Returns an array of QueueMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QueueMessage
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
