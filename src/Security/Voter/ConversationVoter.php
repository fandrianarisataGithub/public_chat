<?php

namespace App\Security\Voter;

use App\Repository\ConversationRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ConversationVoter extends Voter
{
    public const ADD = 'CONV_ADD';
    public const VIEW = 'CONV_VIEW';

    private $repoConv;

    public function __construct(
        ConversationRepository $repoConv
    )
    {
        $this->repoConv = $repoConv;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::ADD, self::VIEW])
            && $subject instanceof \App\Entity\Conversation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $isUserParticipant = $this->repoConv->checkIUserIsParticipantInThisConv($token->getUser(), $subject);

        // if(is_null($isUserParticipant)) return false;
        //if($isUserParticipant) return true;
        return !!$isUserParticipant; 
    }
}
