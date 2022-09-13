<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
class UserVoter extends Voter
{
    
    const VIEW = 'view';
    const DELETE = 'delete';
 
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $user, TokenInterface $token): bool
    {
        
        /** @var Costomer $customer */
        $customer = $token->getUser();

        if (!$customer instanceof Customer) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                
            case self::DELETE:
                return $customer === $user->getCustomer();        }

        throw new \LogicException('This code should not be reached!');
    }
}
