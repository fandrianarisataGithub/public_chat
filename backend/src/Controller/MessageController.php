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

    #[Route('/{id}', name: 'getMessages', methods: ['POST'])]    
    /**
     * Method getAllMessagesInConversation
     *
     * @param Request $request [explicite description]
     * @param Conversation $conversation [explicite description]
     *
     * @return Response
     */
    public function getAllMessagesInConversation(Request $request, Conversation $conv): Response
    {
    
        $requestData = json_decode($request->getContent(), true);
        $messInconv = $this->repoMessage->getMessageInConv($conv);
        //dd($messInconv);
        $currentUser = $this->repoUser->find($requestData['currentUserId']);
        foreach($messInconv as $message){
            if($message->getMessageOwner()->getId() === $currentUser->getId()){
                $message->setIsMyMessage(true);
            }else{
                $message->setIsMyMessage(false);
            }
        }
        //dd($messagesInConv);
        
        return $this->json($messInconv, Response::HTTP_OK, [], [
            'attributes' => ['id','messageOwner' => ['id', 'username'], 'content', 'createdAt', 'isMyMessage', 'conversation' => ['id']]
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
        if(is_null($conversation)){
            throw new \Exception("Error Processing Request", 1);
            
        }
        $requestData = json_decode($request->getContent(), true);
        $currentUser = $this->repoUser->find($requestData['currentUserId']);
        $messageContent = $requestData['content'];
        // si la conversation est null
        if(is_null($conversation)){
            throw new \Exception("La conversation est null", 1);
        }
        
        //assure que l'user peut ajouter un message dans cette conv

        //$this->denyAccessUnlessGranted('CONV_ADD', $conversation);
        
        $message = new Message();
        $message->setContent($messageContent);
        $message->setMessageOwner($currentUser); 
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

        $convParticipation = $this->repoParticipation->getParticipationByConvByCurrentUser(
            $conversation,
            $currentUser
        ); 
        // for test

       /* $convParticipation = $this->repoParticipation->getParticipationByConvByCurrentUser(
            $conversation,
            $usertTest
        );*/

        //fin
        $dataToUpdate = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'messageOwner' => ['id', 'username'], 'createdAt', 'isMyMessage', 'conversation' => ['id']]
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
            'attributes' => ['id', 'content', 'messageOwner' => ['id', 'username'], 'createdAt', 'isMyMessage', 'conversation' => ['id']]
        ]);
    }
}
