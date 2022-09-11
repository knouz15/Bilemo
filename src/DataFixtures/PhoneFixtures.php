<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PhoneFixtures extends Fixture
{
    /**
     * An array which represent data to load.
     *
     * @var array
     */
    private $data = 
    [
        [
            'brand' => 'Samsung',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1049.90,
            'color' => 'White',
            'weight' => 198.00,
            'nfc' => true,
            'model' => 'S10+',
            'resolution' => '1600x720 pixels',
            'storage' => '128 Go'
        ],

        [
            'brand' => 'Samsung',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1049.90,
            'color' => 'grey',
            'weight' => 198.00,
            'nfc' => true,
            'model' => 'S10+',
            'resolution' => '1600x720 pixels',
            'storage' => '128 Go'
        ],

        [
            'brand' => 'Samsung',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1049.90,
            'color' => 'red',
            'weight' => 198.00,
            'nfc' => true,
            'model' => 'S10+',
            'resolution' => '1600x720 pixels',
            'storage' => '128 Go'
        ],

        [
            'brand' => 'Samsung',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1049.90,
            'color' => 'blue',
            'weight' => 198.00,
            'nfc' => true,
            'model' => 'S10+',
            'resolution' => '1600x720 pixels',
            'storage' => '128 Go'
        ],

        [
            'brand' => 'Samsung',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1049.90,
            'color' => 'black',
            'weight' => 198.00,
            'nfc' => true,
            'model' => 'S10+',
            'resolution' => '1600x720 pixels',
            'storage' => '128 Go'
        ],

        [
            'brand' => 'Samsung',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1290.90,
            'color' => 'Black',
            'weight' => 190.00,
            'nfc' => true,
            'model' => 'S10',
            'resolution' => '1600x800 pixels',
            'storage' => '130 Go'
        ],
        
        [
            'brand' => 'Xiaomi',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 359.90,
            'color' => 'Black',
            'weight' => 177.00,
            'nfc' => true,
            'model' => 'M10',
            'resolution' => '1800x720 pixels',
            'storage' => '150 Go'
        ],
        [
            'brand' => 'Xiaomi',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 279.90,
            'color' => 'White',
            'weight' => 120.00,
            'nfc' => false,
            'model' => 'M10 Pro',
            'resolution' => '1900x900 pixels',
            'storage' => '132 Go'
        ],
        [
            'brand' => 'Iphone',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
            'price' => 1179.90,
            'color' => 'Black',
            'weight' => 200.00,
            'nfc' => true,
            'model' => 'II',
            'resolution' => '2000x820 pixels',
            'storage' => '195 Go'
        ]
    ];

    
    public function load(ObjectManager $manager): void
    {
        
        foreach($this->data as $row)
        {
            // $i = rand(0, 9);
            // $customer = $this->getReference(CustomerFixtures::CUSTOMER_REFERENCE . 'customer_' . $i);


            $phone = (new Phone())
                ->setBrand($row['brand'])
                ->setDescription($row['description'])
                ->setPrice($row['price'])
                ->setColor($row['color'])
                ->setWeight($row['weight'])
                ->setNfc($row['nfc'])
                ->setModel($row['model'])
                ->setResolution($row['resolution'])
                ->setStorage($row['storage']);

            $manager->persist($phone);    
        }    

        $manager->flush();
    }
}