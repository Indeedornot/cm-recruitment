<?php

namespace App\Security\Entity;

use App\Entity\ClientApplication;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity]
class Client extends User
{
    #[ORM\OneToMany(targetEntity: ClientApplication::class, mappedBy: 'client', cascade: ['persist', 'remove'])]
    private Collection $applications;

    public function __construct()
    {
        parent::__construct();
        $this->applications = new ArrayCollection();
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = UserRoles::CLIENT->value;
        return array_unique($roles);
    }

    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function setApplications(Collection $applications): Client
    {
        $this->applications = $applications;
        return $this;
    }

    public function addApplication(ClientApplication $application): Client
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
        }
        return $this;
    }

    public function removeApplication(ClientApplication $application): Client
    {
        $this->applications->removeElement($application);
        return $this;
    }
}
