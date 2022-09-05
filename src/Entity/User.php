<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// #[UniqueEntity(fields: ['email'], message: 'Un utilisateur ayant cet email existe')]
#[ORM\Entity(repositoryClass: UserRepository::class)]

class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["listUsers"])]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Groups(["listUsers","showUser"])]
    #[Assert\NotBlank(message: "L'email l'auteur est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Groups(["listUsers","showUser"])]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire au moins {{ limit }} caractère", maxMessage: "Le nom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Groups(["listUsers","showUser"])]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    #[Groups(["showUser"])]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    private ?string $adress = null;

    #[ORM\Column(length: 20)]
    #[Groups(["showUser"])]
    #[Assert\NotBlank(message: "Le code postal est obligatoire")]
    private ?string $zipcode = null;

    #[ORM\Column(length: 50)]
    #[Groups(["showUser"])]
    #[Assert\NotBlank(message: "Le nom de la ville est obligatoire")]
    private ?string $city = null;

    #[ORM\Column(length: 50)]
    #[Groups(["listUsers","showUser"])]
    #[Assert\NotBlank(message: "Le nom du pays est obligatoire")]
    private ?string $country = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["showUser"])]
    private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column(nullable: true)]
    // #[Groups(["showUser"])]
    // private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    // #[ORM\JoinColumn(onDelete:"CASCADE")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;
    

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    // public function getUpdatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->updatedAt;
    // }
 
    // public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    // {
    //     $this->updatedAt = $updatedAt;

    //     return $this;
    // }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCustomer(): ?Customer
    {   
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
