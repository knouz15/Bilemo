<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones"])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones","showPhone"])]
    private ?string $brand = null;

    #[ORM\Column(type: Types::TEXT)]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones","showPhone"])]
    private ?string $description = null;

    #[ORM\Column]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones","showPhone"])]
    private ?float $price = null;

    #[ORM\Column(length: 45)]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones","showPhone"])]
    private ?string $color = null;

    #[ORM\Column]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones"])]
    private ?float $weight = null;

    #[ORM\Column]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones"])]
    private ?bool $nfc = null;

    #[ORM\Column(length: 150)]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones","showPhone"])]
    private ?string $model = null;

    #[ORM\Column(length: 50)]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones"])]
    private ?string $resolution = null;

    #[ORM\Column(length: 45)]
    // #[Groups(["getPhones","getCustomers"])]
    #[Groups(["listPhones"])]
    private ?string $storage = null;

    #[ORM\Column(type: 'datetime_immutable', 
    options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // #[ORM\ManyToOne(inversedBy: 'phones')]
    // #[ORM\JoinColumn(onDelete:"CASCADE")]
    
    // #[Groups(["showPhone"])]
    // private ?Customer $customer = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function isNfc(): ?bool
    {
        return $this->nfc;
    }

    public function setNfc(bool $nfc): self
    {
        $this->nfc = $nfc;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    
    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function setResolution(string $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // public function getCustomer(): ?Customer
    // {
        
    //     return $this->customer;
    // }

    // public function setCustomer(?Customer $customer): self
    // {
    //     $this->customer = $customer;

    //     return $this;
    // }


}
