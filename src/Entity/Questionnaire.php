<?php

namespace App\Entity;

use App\Contract\PhoneNumber\Doctrine\PhoneNumberType;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\PostingRepository;
use libphonenumber\PhoneNumber;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Contract\PhoneNumber\Validator\PhoneNumber as PhoneNumberConstraint;

#[ORM\Entity(repositoryClass: PostingRepository::class)]
class Questionnaire
{
    use Timestampable;
    use Identified;

    #[ORM\Column]
    #[ORM\OneToOne(targetEntity: ClientApplication::class, inversedBy: 'questionnaire')]
    private ClientApplication $client;

    #[Assert\Email]
    #[ORM\Column]
    private string $email;

    #[PhoneNumberConstraint]
    #[ORM\Column(type: PhoneNumberType::NAME)]
    private PhoneNumber $phone;

    #[Assert\Length(min: 2, max: 100)]
    #[ORM\Column]
    private string $firstName;

    #[Assert\Range(min: 0, max: 100)]
    #[ORM\Column]
    private int $age;

    #[Assert\Length(exactly: 11)]
    #[ORM\Column]
    private string $pesel;
    #[ORM\Column]
    private string $lastName;

    #[Assert\Length(min: 1, max: 6)]
    #[ORM\Column]
    private string $houseNo;

    #[Assert\AtLeastOneOf([
        new Assert\IsNull(),
        new Assert\Length(min: 1, max: 100),
    ])]
    #[ORM\Column]
    private ?string $street;

    #[Assert\Length(min: 2, max: 100)]
    #[ORM\Column]
    private string $city;

    #[Assert\Regex(pattern: '/^\d{2}-\d{3}$/')]
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

    public function getPhone(): PhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(PhoneNumber $phone): self
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

    public function getClient(): ClientApplication
    {
        return $this->client;
    }

    public function setClient(ClientApplication $client): self
    {
        $this->client = $client;
        return $this;
    }
}
