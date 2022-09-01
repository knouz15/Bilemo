<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    public const CUSTOMER_REFERENCE = 'customer_';
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0; $i < 10; $i++)
        {
            $customer = (new Customer())
            ->setEmail('customer_'.$i.'@testmail.fr')
            // ->setRoles(["ROLE_USER"])
            ->setSociety('society_'.$i);
            $customer->setPassword($this->userPasswordHasher->hashPassword($customer, "password"))
            ->setPhoneNumber('00'.rand(01100000000,99999999999));
 
            $this->addReference(self::CUSTOMER_REFERENCE.$i, $customer);

            $manager->persist($customer);   
         }

        $manager->flush();
    }
} 
