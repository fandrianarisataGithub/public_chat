<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    private $repoUser;

    public function __construct(
        UserRepository $repoUser
    )
    {
        $this->repoUser = $repoUser;
    }
    /**
     * @Route("/api/login", name="app_api_login", methods={"POST"})
     */
    public function login(
        Request $request,
        JWTTokenManagerInterface $JWTManager,
        UserPasswordHasherInterface $passwordEncoder
    )
    {
        $requestData = json_decode($request->getContent(), true);
        $login = $requestData['login'];
        $plainPassword = $requestData['password'];
        
        // check if the username exist
        $user = $this->repoUser->findUserByLoginRequest($login);
        $error = null;
        if(is_null($user)){
            $error = "Le login ou le mot de passe renseigné n'est pas valide";
            return $this->json([
                'token' => null,
                'error' => $error
            ]);
        }
        // check the password
        if(!$passwordEncoder->isPasswordValid($user, $plainPassword)){
            $error = "Le login ou le mot de passe renseigné n'est pas valide";
            return $this->json([
                'token' => null,
                'error' => $error
            ]);
        }
        $token = $JWTManager->create($user);

        return $this->json([
            'token' => $token,
            'error' => $error
        ]);
    }

    /**
     * @Route("/api/register", name="app_api_register", methods={"POST"})
     */
    public function register(Request $request)
    {
        // Traitez l'enregistrement de l'utilisateur ici
    }
}