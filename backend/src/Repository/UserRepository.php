<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function findUserByLoginRequest($login)
    {
        return $this->createQueryBuilder('u')
           ->where('u.username = :val OR u.email = :val')
           ->setParameter('val', $login)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }
    public function getAllfriends($user)
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->select('u.id as amis_id', 'u.username as username', 'dfrom.status as friend_status')
            ->innerJoin('u.demandFriendFroms', 'dfrom')
            ->where(
                $qb->expr()->neq('u.id', ':myId')
            )
            ->setParameter('myId', $user->getId())
            ->orderBy('u.id', 'ASC')
        ;
        return $qb->getQuery()->getResult();
    }
    public function getAllUsers($currentUser)
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->select('u.id', 'u.username as username')
            ->orderBy('u.id', 'ASC')
        ;
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
