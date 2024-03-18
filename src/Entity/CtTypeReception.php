<?php

namespace App\Entity;

use App\Repository\CtTypeReceptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtTypeReceptionRepository::class)
 */
class CtTypeReception
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
    private $tprcp_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_type_reception_id")
     */
    private $ctReceptions;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_type_reception_id")
     */
    private $ctDroitPTACs;

    public function __construct()
    {
        $this->ctReceptions = new ArrayCollection();
        $this->ctDroitPTACs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTprcpLibelle(): ?string
    {
        return strtoupper($this->tprcp_libelle);
    }

    public function setTprcpLibelle(string $tprcp_libelle): self
    {
        $this->tprcp_libelle = strtoupper($tprcp_libelle);

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
            $ctReception->setCtTypeReceptionId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtTypeReceptionId() === $this) {
                $ctReception->setCtTypeReceptionId(null);
            }
        }

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
            $ctDroitPTAC->setCtTypeReceptionId($this);
        }

        return $this;
    }

    public function removeCtDoitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if ($this->ctDroitPTACs->removeElement($ctDroitPTAC)) {
            // set the owning side to null (unless already changed)
            if ($ctDroitPTAC->getCtTypeReceptionId() === $this) {
                $ctDroitPTAC->setCtTypeReceptionId(null);
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
        return $this->getTprcpLibelle();
    }
}
