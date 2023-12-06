<?php

namespace App\Entity;

use App\Repository\CtMotifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtMotifRepository::class)
 */
class CtMotif
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
    private $mtf_libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mtf_is_calculable;

    /**
     * @ORM\OneToMany(targetEntity=CtMotifTarif::class, mappedBy="ct_motif_id")
     */
    private $ctMotifTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_motif_id")
     */
    private $ctReceptions;

    public function __construct()
    {
        $this->ctMotifTarifs = new ArrayCollection();
        $this->ctReceptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMtfLibelle(): ?string
    {
        return strtoupper($this->mtf_libelle);
    }

    public function setMtfLibelle(string $mtf_libelle): self
    {
        $this->mtf_libelle = strtoupper($mtf_libelle);

        return $this;
    }

    public function isMtfIsCalculable(): ?bool
    {
        return $this->mtf_is_calculable;
    }

    public function setMtfIsCalculable(bool $mtf_is_calculable): self
    {
        $this->mtf_is_calculable = $mtf_is_calculable;

        return $this;
    }

    /**
     * @return Collection<int, CtMotifTarif>
     */
    public function getCtMotifTarifs(): Collection
    {
        return $this->ctMotifTarifs;
    }

    public function addCtMotifTarif(CtMotifTarif $ctMotifTarif): self
    {
        if (!$this->ctMotifTarifs->contains($ctMotifTarif)) {
            $this->ctMotifTarifs[] = $ctMotifTarif;
            $ctMotifTarif->setCtMotifId($this);
        }

        return $this;
    }

    public function removeCtMotifTarif(CtMotifTarif $ctMotifTarif): self
    {
        if ($this->ctMotifTarifs->removeElement($ctMotifTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctMotifTarif->getCtMotifId() === $this) {
                $ctMotifTarif->setCtMotifId(null);
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
            $ctReception->setCtMotifId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtMotifId() === $this) {
                $ctReception->setCtMotifId(null);
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
        return $this->getMtfLibelle();
    }
}
