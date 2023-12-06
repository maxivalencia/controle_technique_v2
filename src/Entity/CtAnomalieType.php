<?php

namespace App\Entity;

use App\Repository\CtAnomalieTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtAnomalieTypeRepository::class)
 */
class CtAnomalieType
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
    private $atp_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtAnomalie::class, mappedBy="ct_anomalie_type_id")
     */
    private $ctAnomalies;

    public function __construct()
    {
        $this->ctAnomalies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAtpLibelle(): ?string
    {
        return strtoupper($this->atp_libelle);
    }

    public function setAtpLibelle(string $atp_libelle): self
    {
        $this->atp_libelle = strtoupper($atp_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtAnomalie>
     */
    public function getCtAnomalies(): Collection
    {
        return $this->ctAnomalies;
    }

    public function addCtAnomaly(CtAnomalie $ctAnomaly): self
    {
        if (!$this->ctAnomalies->contains($ctAnomaly)) {
            $this->ctAnomalies[] = $ctAnomaly;
            $ctAnomaly->setCtAnomalieTypeId($this);
        }

        return $this;
    }

    public function removeCtAnomaly(CtAnomalie $ctAnomaly): self
    {
        if ($this->ctAnomalies->removeElement($ctAnomaly)) {
            // set the owning side to null (unless already changed)
            if ($ctAnomaly->getCtAnomalieTypeId() === $this) {
                $ctAnomaly->setCtAnomalieTypeId(null);
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
        return $this->getAtpLibelle();
    }
}
