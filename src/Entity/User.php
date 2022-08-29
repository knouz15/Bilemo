<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["listUsers"])]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(["listUsers","showUser"])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Groups(["listUsers","showUser"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["listUsers","showUser"])]
    private ?string $lastname = null;

    
    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["showUser"])]
    
    private ?\DateTimeImmutable $createdAt = null;
//rajouter telephone et adresse et firstname
    #[ORM\Column(nullable: true)]
    #[Groups(["showUser"])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(onDelete:"CASCADE")]
    
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["showUser"])]
    private ?Customer $customer = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
