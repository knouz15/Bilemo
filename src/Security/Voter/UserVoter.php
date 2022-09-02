<?php

// src/Security/UserVoter.php
namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
// $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
class UserVoter extends Voter
{
    
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const DELETE = 'delete';
 
    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::DELETE])) {
            return false;
        }

        // only vote on `User` objects
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $user, TokenInterface $token): bool
    {
        // $customer = $token->getCustomer();
        //$this->storeTokenInSession($token);
        /** @var Costomer $customer */
        $customer = $token->getUser();

        if (!$customer instanceof Customer) {
            // the customer must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a User object, thanks to `supports()`
        /** @var User $user */
        // $user = $subject;

        switch ($attribute) {
            case self::VIEW:// on vérifie si on peut voir le détail du user
                
            case self::DELETE:// on vérifie si on peut supprimer
                return $customer === $user->getCustomer();        }

        throw new \LogicException('This code should not be reached!');
    }
}
