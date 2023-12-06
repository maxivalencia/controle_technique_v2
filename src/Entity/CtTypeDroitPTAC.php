<?php

namespace App\Entity;

use App\Repository\CtTypeDroitPTACRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtTypeDroitPTACRepository::class)
 */
class CtTypeDroitPTAC
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
    private $tp_dp_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtDroitPTAC::class, mappedBy="ct_type_droit_ptac_id")
     */
    private $ctDroitPTACs;

    public function __construct()
    {
        $this->ctDroitPTACs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTpDpLibelle(): ?string
    {
        return strtoupper($this->tp_dp_libelle);
    }

    public function setTpDpLibelle(string $tp_dp_libelle): self
    {
        $this->tp_dp_libelle = strtoupper($tp_dp_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtDroitPTAC>
     */
    public function getCtDroitPTACs(): Collection
    {
        return $this->ctDroitPTACs;
    }

    public function addCtDroitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if (!$this->ctDroitPTACs->contains($ctDroitPTAC)) {
            $this->ctDroitPTACs[] = $ctDroitPTAC;
            $ctDroitPTAC->setCtTypeDroitPtacId($this);
        }

        return $this;
    }

    public function removeCtDroitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if ($this->ctDroitPTACs->removeElement($ctDroitPTAC)) {
            // set the owning side to null (unless already changed)
            if ($ctDroitPTAC->getCtTypeDroitPtacId() === $this) {
                $ctDroitPTAC->setCtTypeDroitPtacId(null);
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
        return $this->getTpDpLibelle();
    }
}
