<?php

namespace App\Entity;

use App\Entity\Trait\CreatedByAdmin;
use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\PostingRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Security\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TITLE', fields: ['title'])]
#[UniqueEntity(fields: ['title'], message: 'A posting with that title already exists')]
#[ORM\Entity(repositoryClass: PostingRepository::class)]
class Questionnaire
{
    use CreatedByAdmin;
    use Timestampable;
    use Identified;

    #[ORM\OneToOne(targetEntity: ClientApplication::class, inversedBy: 'questionnaire')]
    private ClientApplication $client;

    #[Assert\Email]
    #[ORM\Column]
    private string $email;
    #[Assert\Regex(pattern: '/^(\+\d{2} )?\d{3} \d{3} \d{3}$/')]
    #[ORM\Column]
    private string $phone;
    #[ORM\Column]
    private string $firstName;
    #[ORM\Column]
    private int $age;
    #[ORM\Column]
    private string $pesel;
    #[ORM\Column]
    private string $lastName;
    #[ORM\Column]
    private string $houseNo;
    #[ORM\Column]
    private ?string $street;
    #[ORM\Column]
    private string $city;
    #[ORM\Column]
    private string $postalCode;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getHouseNo(): string
    {
        return $this->houseNo;
    }

    public function setHouseNo(string $houseNo): self
    {
        $this->houseNo = $houseNo;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }
}
