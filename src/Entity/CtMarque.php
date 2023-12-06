<?php

namespace App\Entity;

use App\Repository\CtMarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtMarqueRepository::class)
 */
class CtMarque
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
    private $mrq_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtVehicule::class, mappedBy="ct_marque_id")
     */
    private $ctVehicules;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDedCarac::class, mappedBy="ct_marque_id")
     */
    private $ctConstAvDedCaracs;

    public function __construct()
    {
        $this->ctVehicules = new ArrayCollection();
        $this->ctConstAvDedCaracs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMrqLibelle(): ?string
    {
        return strtoupper($this->mrq_libelle);
    }

    public function setMrqLibelle(string $mrq_libelle): self
    {
        $this->mrq_libelle = strtoupper($mrq_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtVehicule>
     */
    public function getCtVehicules(): Collection
    {
        return $this->ctVehicules;
    }

    public function addCtVehicule(CtVehicule $ctVehicule): self
    {
        if (!$this->ctVehicules->contains($ctVehicule)) {
            $this->ctVehicules[] = $ctVehicule;
            $ctVehicule->setCtMarqueId($this);
        }

        return $this;
    }

    public function removeCtVehicule(CtVehicule $ctVehicule): self
    {
        if ($this->ctVehicules->removeElement($ctVehicule)) {
            // set the owning side to null (unless already changed)
            if ($ctVehicule->getCtMarqueId() === $this) {
                $ctVehicule->setCtMarqueId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDedCarac>
     */
    public function getCtConstAvDedCaracs(): Collection
    {
        return $this->ctConstAvDedCaracs;
    }

    public function addCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if (!$this->ctConstAvDedCaracs->contains($ctConstAvDedCarac)) {
            $this->ctConstAvDedCaracs[] = $ctConstAvDedCarac;
            $ctConstAvDedCarac->setCtMarqueId($this);
        }

        return $this;
    }

    public function removeCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if ($this->ctConstAvDedCaracs->removeElement($ctConstAvDedCarac)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDedCarac->getCtMarqueId() === $this) {
                $ctConstAvDedCarac->setCtMarqueId(null);
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
        return $this->getMrqLibelle();
    }
}
