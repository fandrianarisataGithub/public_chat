<?php

namespace App\Repository;

use App\Entity\DemandFriendFor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandFriendFor>
 *
 * @method DemandFriendFor|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandFriendFor|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandFriendFor[]    findAll()
 * @method DemandFriendFor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandFriendForRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandFriendFor::class);
    }
    public function checkIfThereIsADemandByThisUser($demUser, $userCible, $demandStatus)
    {
        $qb = $this->createQueryBuilder('dFor');
        $qb
            ->select("
                dFor.id as demandForId, 
                theDemand.status as demandStatus,
                dFor.status as userCibleStatusDemand
            ")
            ->innerJoin('dFor.user', 'userCible')
            ->innerJoin('dFor.demandFriendFrom', 'theDemand')
            ->innerJoin('theDemand.user', 'demUser')
            ->where(
                $qb->expr()->eq('dFor.user', ':userCible'),
                $qb->expr()->eq('demUser', ':demUser')
            )
            ->andWhere('theDemand.status = :status AND  dFor.status = :status')
            ->setParameters([
                'demUser' => $demUser->getId(),
                'userCible' => $userCible->getId(),
                'status' => $demandStatus
            ])
        ;
        return $qb->getQuery()->getOneOrNullResult();

    }

//    /**
//     * @return DemandFriendFor[] Returns an array of DemandFriendFor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DemandFriendFor
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
