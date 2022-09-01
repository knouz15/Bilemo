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
            
                $customer = $this->getReference(CustomerFixtures::CUSTOMER_REFERENCE . rand(1, 9));
                $user = (new User())
                ->setEmail('user_' . $i . '@myemail.fr')                
                ->setLastname('lastname_' . $i)
                ->setFirstname('firstname_' . $i)
                ->setAdress('adress_' . $i)
                ->setZipcode(rand(100, 10000))
                ->setCity('city_' . $i)
                ->setCountry('country_' . $i)
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