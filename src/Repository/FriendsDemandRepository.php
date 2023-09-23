<?php

namespace App\Repository;

use App\Entity\FriendsDemand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FriendsDemand>
 *
 * @method FriendsDemand|null find($id, $lockMode = null, $lockVersion = null)
 * @method FriendsDemand|null findOneBy(array $criteria, array $orderBy = null)
 * @method FriendsDemand[]    findAll()
 * @method FriendsDemand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendsDemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FriendsDemand::class);
    }

//    /**
//     * @return FriendsDemand[] Returns an array of FriendsDemand objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FriendsDemand
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
