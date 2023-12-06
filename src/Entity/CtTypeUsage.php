<?php

namespace App\Entity;

use App\Repository\CtTypeUsageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtTypeUsageRepository::class)
 */
class CtTypeUsage
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
    private $tpu_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtUsage::class, mappedBy="ct_type_usage_id")
     */
    private $ctUsages;

    public function __construct()
    {
        $this->ctUsages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTpuLibelle(): ?string
    {
        return strtoupper($this->tpu_libelle);
    }

    public function setTpuLibelle(string $tpu_libelle): self
    {
        $this->tpu_libelle = strtoupper($tpu_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtUsage>
     */
    public function getCtUsages(): Collection
    {
        return $this->ctUsages;
    }

    public function addCtUsage(CtUsage $ctUsage): self
    {
        if (!$this->ctUsages->contains($ctUsage)) {
            $this->ctUsages[] = $ctUsage;
            $ctUsage->setCtTypeUsageId($this);
        }

        return $this;
    }

    public function removeCtUsage(CtUsage $ctUsage): self
    {
        if ($this->ctUsages->removeElement($ctUsage)) {
            // set the owning side to null (unless already changed)
            if ($ctUsage->getCtTypeUsageId() === $this) {
                $ctUsage->setCtTypeUsageId(null);
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
        return $this->getTpuLibelle();
    }
}
