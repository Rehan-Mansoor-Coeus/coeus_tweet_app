<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TweetVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {

        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof \App\Entity\Tweet;
    }

    protected function voteOnAttribute(string $attribute, $tweet, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'DELETE' || 'EDIT':
            return $tweet->getUser()->getId() == $user->getId();
        }

        return false;
    }
}
