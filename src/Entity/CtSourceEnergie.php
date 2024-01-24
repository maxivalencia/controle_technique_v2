<?php

namespace App\Entity;

use App\Repository\CtSourceEnergieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtSourceEnergieRepository::class)
 */
class CtSourceEnergie
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
    private $sre_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="ct_source_energie_id")
     */
    private $ctCarteGrises;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDedCarac::class, mappedBy="ct_source_energie_id")
     */
    private $ctConstAvDedCaracs;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_source_energie_id")
     */
    private $ctReceptions;

    public function __construct()
    {
        $this->ctCarteGrises = new ArrayCollection();
        $this->ctConstAvDedCaracs = new ArrayCollection();
        $this->ctReceptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSreLibelle(): ?string
    {
        return strtoupper($this->sre_libelle);
    }

    public function setSreLibelle(string $sre_libelle): self
    {
        $this->sre_libelle = strtoupper($sre_libelle);

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
            $ctCarteGrise->setCtSourceEnergieId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCtSourceEnergieId() === $this) {
                $ctCarteGrise->setCtSourceEnergieId(null);
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
            $ctConstAvDedCarac->setCtSourceEnergieId($this);
        }

        return $this;
    }

    public function removeCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if ($this->ctConstAvDedCaracs->removeElement($ctConstAvDedCarac)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDedCarac->getCtSourceEnergieId() === $this) {
                $ctConstAvDedCarac->setCtSourceEnergieId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtReception>
     */
    public function getCtReceptions(): Collection
    {
        return $this->ctReceptions;
    }

    public function addCtReception(CtReception $ctReception): self
    {
        if (!$this->ctReceptions->contains($ctReception)) {
            $this->ctReceptions[] = $ctReception;
            $ctReception->setCtSourceEnergieId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtSourceEnergieId() === $this) {
                $ctReception->setCtSourceEnergieId(null);
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
        return $this->getSreLibelle();
    }
}
