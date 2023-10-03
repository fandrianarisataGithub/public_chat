<?php

namespace App\Controller;

use App\Entity\User;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Configuration;
use App\Repository\UserRepository;
use Symfony\Component\WebLink\Link;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Repository\MessageRepository;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use App\Repository\ParticipationRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $repoConv;
    private $em;
    private $repoUser;
    private $repoMessage;
    private $repoParticipation;
    private $serializer;
    private $publisher;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repoUser,
        ConversationRepository $repoConv,
        MessageRepository $repoMessage,
        ParticipationRepository $repoParticipation,
        SerializerInterface $serializer,
        PublisherInterface $publisher
    )
    {
        $this->repoConv = $repoConv;
        $this->repoUser = $repoUser;
        $this->repoMessage = $repoMessage;
        $this->repoParticipation = $repoParticipation;
        $this->em = $em;
    }
    
    #[Route('/profile/users/{id}', name: 'getUsersByUser', methods:['GET'])]
    public function getUsersByUser(Request $request, User $currentUser)
    {
       
        $allFriends = $this->repoUser->getAllfriends($currentUser);

        return $this->json($allFriends);
    }
    #[Route('/profile/users', name: 'getUsers', methods:['POST'])]
    public function getUsers(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        //dd($request);
        $currentUser = $this->repoUser->find($requestData['currentUserId']);
        $allUsers = $this->repoUser->getAllUsers($currentUser);

        return $this->json($allUsers);
    }

}
