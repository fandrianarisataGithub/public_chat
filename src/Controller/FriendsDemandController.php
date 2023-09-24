<?php

namespace App\Controller;

use App\Entity\DemandFriendFor;
use App\Entity\DemandFriendFrom;
use App\Entity\User;
use App\Repository\DemandFriendForRepository;
use App\Repository\DemandFriendFromRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FriendsDemandRepository;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/profile/friends', name: 'app_friends.')]
class FriendsDemandController extends AbstractController
{
    private $em;
    private $repoUser;
    private $repoDemandFriendFrom;
    private $repoDemandFriendFor;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repoUser,
        DemandFriendFromRepository $repoDemandFriendFrom,
        DemandFriendForRepository $repoDemandFriendFor
    )
    {
        $this->em = $em;
        $this->repoUser = $repoUser;
        $this->repoDemandFriendFrom = $repoDemandFriendFrom;
        $this->repoDemandFriendFor = $repoDemandFriendFor;
    }

    #[Route('/demand-for/{id}', name: 'createFriends', methods:['GET'])]
    public function index(User $user=null): Response
    {
        $currentUser = $this->getUser();
        //dd($currentUser);
        // il faut que le $userDest exist
        if(is_null($user)){
            throw new \Exception("La personne que vous voulez contacter n'existe pas", 1);
        }

        // je peux pas envoyer un message à moi même
        if($currentUser->getId() == $user->getId()){
            throw new \Exception("On va pas faire une demande à vous même", 1);
        }

        // voir s'il ya déjà une demand d'amis envoyé qui n'est pas encore acceptée 
        //dd($user);
        $isDemandExist = $this->repoDemandFriendFor->checkIfThereIsADemandByThisUser($currentUser, $user, 'en attente');
        //dd($isDemandExist);
        if(is_null($isDemandExist)){
            // faire la demande
            $demand = new DemandFriendFrom();
            $demand->setUser($currentUser);
            $demand->setStatus('en attente');

            // faire la demande for (cible)

            $demandFor = new DemandFriendFor();
            $demand->setUser($user);
            $demandFor->setStatus('en attente');
            $demand->addDemandFriendFor($demandFor);

            // on va faire une transaction pour mieux gérer les erreur
            $this->em->getConnection()->beginTransaction();

            try {
                $this->em->persist($demand);
                $this->em->persist($demandFor);
                $this->em->flush();
                $this->em->commit();

            } catch (\Throwable $th) { // capture de \Exception et Error
                $this->em->rollback();
                throw $th;
            }

            return $this->json([
                    'id_demand_for' => $demandFor->getId(),
                    'demand_status' => $demand->getStatus(),
                    'demand_for_status' => $demandFor->getStatus()
                ], Response::HTTP_CREATED, [], [])
            ;

        }else{
            throw new \Exception("La demande d'amis existe déjà", 1);
        }
    }

    #[Route('/accept-demand-from/{id}', name: 'acceptAsFriends', methods:['GET'])]
    public function acceptDemandFromUser(User $user = null, Request $request)
    {
        $userDemander = $user ? $user : null;
        $me = $this->getUser();
        if(is_null($userDemander)){
            throw new \Exception("Le demandeur d'amis n'exist pas en tant que User", 1);
        }
        
        // on cherche la demande venant de cet user
        $theDemandFromUserForMe = $this->repoDemandFriendFrom->findTheDemandFromThisUserForMe($userDemander, $me);
        //dd($theDemandFromUserForMe);
        if(is_null($theDemandFromUserForMe)){
            throw new \Exception("Il n'y a pas encore de demande faite", 1);
        }

        // sinon, on set les status de ces demandes

        $demandFrom = $this->repoDemandFriendFrom->find($theDemandFromUserForMe['demandFromId']);
        $demandFor = $this->repoDemandFriendFor->find($theDemandFromUserForMe['demandForId']);

        $demandFrom->setStatus('accepted');
        $demandFor->setStatus('accepted');

        $this->em->flush();

        return $this->json([
                'id_demand_for' => $demandFor->getId(),
                'id_demand_from' => $demandFrom->getId(),
                'demand_for_status' => $demandFor->getStatus()
            ], Response::HTTP_OK, [], [])
        ;
    }
}
