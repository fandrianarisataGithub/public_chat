<?php

namespace App\Controller;
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

class PageController extends AbstractController
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
    
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // renvoyer vers le login si pas connecté
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $key = InMemory::base64Encoded(
            $this->getParameter('mercure_secret_key')
        );
        //$username = $this->getUser()->getUsername(); //foe test
        $usertTest = $this->repoUser->find(3);
        $username = $usertTest->getUsername(); 
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('testing'));
        $token = $config->builder()
                ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
                ->getToken(
                    new Sha256(),
                    $key
                )
        ;
        //dd($token->toString());

        $response =  $this->render('page/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token->toString(),
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );
        //dd($response);

        return $response;
    }

    #[Route('/test', name: 'app_home_test')]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            ['https://example.com/my-private-topic'],
            json_encode(['status' => 'tay be'])
        );
        //dd($hub);
        $hub->publish($update);

        return new Response('published!');
    }

    #[Route('/subscribe', name: 'example.topic', methods:['GET'])]
    public function testTopics(Request $request)
    {

        return $this->render('page/test.html.twig');
    }

}
