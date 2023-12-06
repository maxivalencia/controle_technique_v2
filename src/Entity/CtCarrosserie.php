<?php

namespace App\Entity;

use App\Repository\CtCarrosserieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtCarrosserieRepository::class)
 */
class CtCarrosserie
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
    private $crs_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="ct_carrosserie_id")
     */
    private $ctCarteGrises;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDedCarac::class, mappedBy="ct_carrosserie_id")
     */
    private $ctConstAvDedCaracs;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_carrosserie_id")
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

    public function getCrsLibelle(): ?string
    {
        return strtoupper($this->crs_libelle);
    }

    public function setCrsLibelle(string $crs_libelle): self
    {
        $this->crs_libelle = strtoupper($crs_libelle);

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
            $ctCarteGrise->setCtCarrosserieId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCtCarrosserieId() === $this) {
                $ctCarteGrise->setCtCarrosserieId(null);
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
            $ctConstAvDedCarac->setCtCarrosserieId($this);
        }

        return $this;
    }

    public function removeCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if ($this->ctConstAvDedCaracs->removeElement($ctConstAvDedCarac)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDedCarac->getCtCarrosserieId() === $this) {
                $ctConstAvDedCarac->setCtCarrosserieId(null);
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
            $ctReception->setCtCarrosserieId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtCarrosserieId() === $this) {
                $ctReception->setCtCarrosserieId(null);
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
        return $this->getCrsLibelle();
    }
}
