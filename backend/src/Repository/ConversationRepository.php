<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }
    public function findConvBetweenCurrentUserAndOther($currentUser, $otherUser)
    {
        $qb = $this->createQueryBuilder('conv');

        $qb
            ->select($qb->expr()->count('p.conversations'))
            ->innerJoin('conv.participations', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.participant', ':me'),
                    $qb->expr()->eq('p.participant', ':otherUser'),
                )
            )
            ->groupBy('p.conversations')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversations'),
                    2
                )
            )
            ->setParameters([
                'me' => $currentUser->getId(),
                'otherUser' => $otherUser->getId()
            ])
        ;

        return $qb->getQuery()->getResult();

    }
    public function getAllCurrentUserConversations($currentUser)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->select('
                otherUser.username as otherUserUsernme',
                'c.id as convId',
                'lm.content',
                'lm.createdAt'
            )
            ->innerJoin(
                'c.participations', 
                'otherUserParticipation', 
                'WITH',
                $qb->expr()->neq('otherUserParticipation.participant', ':currentUserId')
            )
            ->innerJoin(
                'c.participations', 
                'myParticipation', 
                'WITH',
                $qb->expr()->eq('myParticipation.participant', ':currentUserId')
            )
            ->leftJoin('c.lastMessage', 'lm')
            ->innerJoin('otherUserParticipation.participant', 'otherUser')
            ->innerJoin('myParticipation.participant', 'me')
        
            ->andwhere('me.id = :currentUserId')
            ->setParameters([
                'currentUserId' => $currentUser->getId(),
            ])
            ->orderBy('lm.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
    
    public function checkIUserIsParticipantInThisConv($currentUser, $conv)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->innerJoin('c.participations', 'p')
            ->innerJoin('p.participant', 'currentUser')
            ->innerJoin('p.conversations', 'conv')
            ->where(
                $qb->expr()->eq('conv.id', ':convId'),
                $qb->expr()->eq('currentUser.id', ':currentUserId')
            )
            ->setParameters([
                'convId' => $conv->getId(),
                'currentUserId' => $currentUser->getId()
            ])
        ;
        return $qb->getQuery()->getOneOrNullResult();

    }


//    /**
//     * @return Conversation[] Returns an array of Conversation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conversation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
