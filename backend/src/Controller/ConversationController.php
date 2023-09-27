<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conversation;
use App\Entity\Participation;
use App\Repository\UserRepository;
use Symfony\Component\WebLink\Link;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Migrations\Tools\TransactionHelper;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile/conversation', name: 'app_conversation.')]
class ConversationController extends AbstractController
{
    private $repoUser;
    private $repoConv;
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repoUser,
        ConversationRepository $repoConv
    )
    {
        $this->em = $em;
        $this->repoUser = $repoUser;
        $this->repoConv = $repoConv;
    }

    #[Route('/new', name: 'new', methods:['POST'])]    
    /**
     * Method new
     *
     * @param Request $request [explicite description]
     *
     * @return JsonResponse
     */
    public function new(Request $request): JsonResponse
    {
        $currentUser = $this->getUser();
        $otherUser = $this->repoUser->find($request->get('user_id'));

        // il faut que ce $otherUser exist
        if(is_null($otherUser)){
            throw new \Exception("Le user dans le quel on voudra entamer une discussion n'existe pas", 1);
        }

        // je ne doid pas faire une discussion à moi même
        if($otherUser->getId() === $currentUser->getId()){
            throw new \Exception("Faut pas faire une discussion de moi vers moi", 1);
        }

        // check si il y a déjà une conversation entre ces users

        $convBetweenCurrentUserAndOther = $this->repoConv->findConvBetweenCurrentUserAndOther(
            $currentUser,
            $otherUser
        );

        // si la conversation a déjà eu lieu
        if(count($convBetweenCurrentUserAndOther)){
            throw new \Exception("Une ligne de cette conversation existe déjà pour moi et pour le user_id =".$otherUser->getId() , 1);
        }

        // on crée la conversaiton avec 2 participation par les 2 users

        $conv = new Conversation();
        
        // ma participation
        $participation = new Participation();
        $participation->setParticipant($currentUser);
        $participation->setConversations($conv);

        //the other partiipation
        $otherParticipation = new Participation();
        $otherParticipation->setParticipant($otherUser);
        $otherParticipation->setConversations($conv);

        // faire une transation pour les eurreurs

        $this->em->getConnection()->beginTransaction();

        try {
            $this->em->persist($conv);
            $this->em->persist($participation);
            $this->em->persist($otherParticipation);
            $this->em->flush();
            $this->em->commit();
        } catch (\Throwable $th) {
            $this->em->rollback();
            throw $th;
        }

        return $this->json(['id' => $conv->getId()], Response::HTTP_CREATED, [], []);
    }

    #[Route('/', name: 'getConversations', methods:['GET'])]
    public function getConversations(Request $request): JsonResponse 
    {
        $currentUser = $this->getUser();
        // pour test
        //$usertTest = $this->repoUser->find(3);
        // fin test
        $conversations = $this->repoConv->getAllCurrentUserConversations($currentUser);
        //dd($conversations);
        // l'url hub de mercure à partir du parameer de symfony

        $hubUrl = $this->getParameter('mercure.default_hub');

        // en-tête de lien (Link) à la réponse HTTP
        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json($conversations);
    }
}
