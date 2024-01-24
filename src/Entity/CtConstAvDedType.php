<?php

namespace App\Entity;

use App\Repository\CtConstAvDedTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtConstAvDedTypeRepository::class)
 */
class CtConstAvDedType
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
    private $cad_tp_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDedCarac::class, mappedBy="ct_const_av_ded_type_id")
     */
    private $ctConstAvDedCaracs;

    public function __construct()
    {
        $this->ctConstAvDedCaracs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCadTpLibelle(): ?string
    {
        return strtoupper($this->cad_tp_libelle);
    }

    public function setCadTpLibelle(string $cad_tp_libelle): self
    {
        $this->cad_tp_libelle = strtoupper($cad_tp_libelle);

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
            $ctConstAvDedCarac->setCtConstAvDedTypeId($this);
        }

        return $this;
    }

    public function removeCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if ($this->ctConstAvDedCaracs->removeElement($ctConstAvDedCarac)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDedCarac->getCtConstAvDedTypeId() === $this) {
                $ctConstAvDedCarac->setCtConstAvDedTypeId(null);
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
        return $this->getCadTpLibelle();
    }
}
