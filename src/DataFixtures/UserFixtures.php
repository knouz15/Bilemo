<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\DataFixtures\DependentFixturesInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            if ($i < 10) {
                $customer = $this->getReference(CustomerFixtures::CUSTOMER_REFERENCE . 'customer_0');
            } 
            else {
                $customer = $this->getReference(CustomerFixtures::CUSTOMER_REFERENCE . 'customer_' . rand(1, 9));
            }
            $user = (new User())
                ->setUsername('username_' . $i)
                ->setLastname('lastname_' . $i)
                ->setPassword('password_' . $i)
                //->setPassword($this->userPasswordHasher->hashPassword($user, 'password_' . $i))
                ->setEmail('user_' . $i . '@myemail.fr')
                ->setCustomer($customer);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class
        ];
    }
}