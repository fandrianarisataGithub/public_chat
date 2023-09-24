<?php

namespace App\Repository;

use App\Entity\DemandFriendFrom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandFriendFrom>
 *
 * @method DemandFriendFrom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandFriendFrom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandFriendFrom[]    findAll()
 * @method DemandFriendFrom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandFriendFromRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandFriendFrom::class);
    }
    public function findTheDemandFromThisUserForMe($userDemander, $me)
    {
        $qb = $this->createQueryBuilder('demandFrom');
        $qb 
            ->select('demandFrom.id as demandFromId, demandFor.id as demandForId, userDemandeur.id as userDemandeurId, userCible.id as userCibleId')
            //->select('demandFrom')
            ->innerJoin('demandFrom.user', 'userDemandeur')
            ->innerJoin('App\Entity\DemandFriendFor', 'demandFor', 'WITH', 'demandFor.demandFriendFrom = demandFrom.id')
            ->innerJoin('demandFor.user', 'userCible')
            ->where(
                $qb->expr()->eq('userDemandeur.id', ':theUserDemandeur'),
                $qb->expr()->eq('userCible.id', ':myId')
            )
            ->setParameters([
                'theUserDemandeur' => $userDemander->getId(),
                'myId' => $me->getId()
            ])
           
        ;
        
        return $qb->getQuery()->getOneOrNullResult();
        
    }

//    /**
//     * @return DemandFriendFrom[] Returns an array of DemandFriendFrom objects
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

//    public function findOneBySomeField($value): ?DemandFriendFrom
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
