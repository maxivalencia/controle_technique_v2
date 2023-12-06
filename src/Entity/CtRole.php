<?php

namespace App\Entity;

use App\Repository\CtRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtRoleRepository::class)
 */
class CtRole
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role_name;

    /**
     * @ORM\OneToMany(targetEntity=CtUser::class, mappedBy="ct_role_id")
     */
    private $ctUsers;

    public function __construct()
    {
        $this->ctUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName(): ?string
    {
        return strtoupper($this->role_name);
    }

    public function setRoleName(string $role_name): self
    {
        $this->role_name = strtoupper($role_name);

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getRoleName();
    }

    /**
     * @return Collection<int, CtUser>
     */
    public function getCtUsers(): Collection
    {
        return $this->ctUsers;
    }

    public function addCtUser(CtUser $ctUser): self
    {
        if (!$this->ctUsers->contains($ctUser)) {
            $this->ctUsers[] = $ctUser;
            $ctUser->setCtRoleId($this);
        }

        return $this;
    }

    public function removeCtUser(CtUser $ctUser): self
    {
        if ($this->ctUsers->removeElement($ctUser)) {
            // set the owning side to null (unless already changed)
            if ($ctUser->getCtRoleId() === $this) {
                $ctUser->setCtRoleId(null);
            }
        }

        return $this;
    }
}
