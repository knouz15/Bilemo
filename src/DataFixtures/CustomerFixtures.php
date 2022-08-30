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
            // ->setUsername('customer_'.$i)
            ->setEmail('customer_'.$i.'@testmail.fr')
            ->setRoles(["ROLE_USER"])
            ->setSociety('society_'.$i)
            ->setPassword("password")
            // ->setPassword($this->userPasswordHasher->hashPassword($customer, "password"))
            // ->setPassword($this->userPasswordHasher->hashPassword($customer, 'password_' . $i))
            ->setPhoneNumber('00'.rand(01100000000,99999999999));
 
            $this->addReference(self::CUSTOMER_REFERENCE.$customer->getUsername(), $customer);

            $manager->persist($customer); 
            
            // Création d'un admin
            // $admin = new Customer();
            // $admin->setEmail('admin@mymail.fr');
            // $admin->setRoles(["ROLE_ADMIN"]);
            // $admin->setSociety('My Society');
            // $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
            // $admin->setPhoneNumber('0033111111111');

            // $this->addReference(self::CUSTOMER_REFERENCE.$admin->getUsername(), $admin);

            // $manager->persist($admin);
         }

        $manager->flush();
     }
} 