<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use PhpParser\Node\Stmt\TryCatch;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use App\Repository\ParticipationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;

#[Route('/profile/message', name: 'app_message.')]
class MessageController extends AbstractController
{
    const SERIALIZE_DATA = ['id', 'content', 'createdAt', 'isMyMessage'];
    private $repoConv;
    private $em;
    private $repoUser;
    private $repoMessage;
    private $repoParticipation;
    private $publisher;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repoUser,
        ConversationRepository $repoConv,
        MessageRepository $repoMessage,
        ParticipationRepository $repoParticipation,
    )
    {
        $this->repoConv = $repoConv;
        $this->repoUser = $repoUser;
        $this->repoMessage = $repoMessage;
        $this->repoParticipation = $repoParticipation;
        $this->em = $em;
    }

    #[Route('/{id}', name: 'getMessages', methods: ['GET'])]    
    /**
     * Method getAllMessagesInConversation
     *
     * @param Request $request [explicite description]
     * @param Conversation $conversation [explicite description]
     *
     * @return Response
     */
    public function getAllMessagesInConversation(Request $request, Conversation $conversation): Response
    {
        // bloquee les accèss de cette fonctionnalité si c'est pas le bon user propiétaire du compte
        // ie si le this->getUser() n'est pas un participant à cette conversation 
        $this->denyAccessUnlessGranted('CONV_VIEW', $conversation);

        $messagesInConv = $this->repoMessage->findBy([
            'conversation' => $conversation
        ]);

        //dd($messagesInConv);
        // on différencie les messages (mes mess et les mess de l'autre user) 
        // on a crée un attr isMyMessage dans l'entity Message
        // set if the message is mine or not

        foreach($messagesInConv as $message){
            if($message->getMessageOwner()->getId() === $this->getUser()->getId()){
                $message->setIsMyMessage(true);
            }else{
                $message->setIsMyMessage(false);
            }
        }
        //dd($messagesInConv);
        
        return $this->json($messagesInConv, Response::HTTP_OK, [], [
            'attributes' => self::SERIALIZE_DATA
        ]);
    }

    #[Route('/new/{id}', name: 'newMessage', methods: ['POST'])]     
    /**
     * Method newMessage
     *
     * @param Request $request [explicite description]
     * @param Conversation $conversation [explicite description]
     *
     * @return void
     */
    public function newMessage(Request $request, 
        Conversation $conversation = null, 
        SerializerInterface $serializer,
        HubInterface $hub
    )
    {
        
        // si la conversation est null
        if(is_null($conversation)){
            throw new \Exception("La conversation est null", 1);
        }
        
        //assure que l'user peut ajouter un message dans cette conv

        //$this->denyAccessUnlessGranted('CONV_ADD', $conversation);
        $messageContent = $request->get('content');

        $message = new Message();
        $message->setContent($messageContent);
        //$message->setMessageOwner($this->getUser()); // replace after test in postman
        $usertTest = $this->repoUser->find(3);
        $message->setMessageOwner($usertTest);
        // fin
        $message->setIsMyMessage(true);
        $conversation->setLastMessage($message); 
        $conversation->addMessage($message);

        // transaction
        $this->em->getConnection()->beginTransaction();

        try {
            $this->em->persist($message);
            $this->em->persist($conversation);
            $this->em->commit();
        } catch (\Throwable $th) {
            $this->em->rollback();
            throw $th;
        }

        /*$currentUser = $this->getUser();

        $convParticipation = $this->repoParticipation->getParticipationByConvByCurrentUser(
            $conversation,
            $currentUser
        );*/ // for test

        $convParticipation = $this->repoParticipation->getParticipationByConvByCurrentUser(
            $conversation,
            $usertTest
        );

        //fin
        $dataToUpdate = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'isMyMessage', 'conversation' => ['id']]
        ]);

        $update = new Update(
            [
                sprintf("/profile/conversation/%s", $conversation->getId()),
                sprintf("/profile/conversation/%s", $convParticipation->getParticipant()->getUsername()),
            ],
            $dataToUpdate,
            true,
            sprintf("/profile/conversation/%s", $convParticipation->getParticipant()->getUsername())
        );
        //dd($update);
        $hub->publish($update);
    
        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::SERIALIZE_DATA
        ]);
    }
}
