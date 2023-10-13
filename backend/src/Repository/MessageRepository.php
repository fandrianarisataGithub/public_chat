<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
    public function getMessageInConv($conv)
    {
        return $this->createQueryBuilder('m')
            
            ->leftJoin('m.messageOwner', 'user')
            ->leftJoin('m.conversation', 'conversation')
            ->andWhere('conversation.id = :convId')
            ->setParameter('convId', $conv->getId())
            ->getQuery()
            ->getResult()
       ;
    }
    public function getConversationBetweenThisUserAndMe($currentUser, $otherUser)
    {
        $qb = $this->createQueryBuilder('m');
        $qb 
            ->select('conversation.id as conversationId')
            ->innerJoin('m.messageOwner', 'user')
            ->innerJoin('m.conversation', 'conversation')
            ->innerJoin('conversation.participations', 'participation')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->andX('user.id = :myId', 'participation.participant = :otherUserId')
                    ,
                    $qb->expr()->andX('user.id = :otherUserId', 'participation.participant = :myId')
                )
            )
            ->setParameters(
                [
                    'otherUserId' => $otherUser->getId(),
                    'myId' => $currentUser->getId()
                ]
            )
            ->groupBy('conversation.id')
        ;
        return $qb->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
