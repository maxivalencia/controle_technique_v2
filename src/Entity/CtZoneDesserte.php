<?php

namespace App\Entity;

use App\Repository\CtZoneDesserteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtZoneDesserteRepository::class)
 */
class CtZoneDesserte
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
    private $zd_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="ct_zone_desserte_id")
     */
    private $ctCarteGrises;

    public function __construct()
    {
        $this->ctCarteGrises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZdLibelle(): ?string
    {
        return strtoupper($this->zd_libelle);
    }

    public function setZdLibelle(string $zd_libelle): self
    {
        $this->zd_libelle = strtoupper($zd_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtCarteGrise>
     */
    public function getCtCarteGrises(): Collection
    {
        return $this->ctCarteGrises;
    }

    public function addCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if (!$this->ctCarteGrises->contains($ctCarteGrise)) {
            $this->ctCarteGrises[] = $ctCarteGrise;
            $ctCarteGrise->setCtZoneDesserteId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCtZoneDesserteId() === $this) {
                $ctCarteGrise->setCtZoneDesserteId(null);
            }
        }

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getZdLibelle();
    }
}
