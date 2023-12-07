<?php

namespace App\Entity;

use App\Repository\CtTypeVisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtTypeVisiteRepository::class)
 */
class CtTypeVisite
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
    private $tpv_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtUsageTarif::class, mappedBy="ct_type_visite_id")
     */
    private $ctUsageTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_type_visite_id")
     */
    private $ctVisites;

    public function __construct()
    {
        $this->ctUsageTarifs = new ArrayCollection();
        $this->ctVisites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTpvLibelle(): ?string
    {
        return strtoupper($this->tpv_libelle);
    }

    public function setTpvLibelle(string $tpv_libelle): self
    {
        $this->tpv_libelle = strtoupper($tpv_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtUsageTarif>
     */
    public function getCtUsageTarifs(): Collection
    {
        return $this->ctUsageTarifs;
    }

    public function addCtUsageTarif(CtUsageTarif $ctUsageTarif): self
    {
        if (!$this->ctUsageTarifs->contains($ctUsageTarif)) {
            $this->ctUsageTarifs[] = $ctUsageTarif;
            $ctUsageTarif->setCtTypeVisiteId($this);
        }

        return $this;
    }

    public function removeCtUsageTarif(CtUsageTarif $ctUsageTarif): self
    {
        if ($this->ctUsageTarifs->removeElement($ctUsageTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctUsageTarif->getCtTypeVisiteId() === $this) {
                $ctUsageTarif->setCtTypeVisiteId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtVisite>
     */
    public function getCtVisites(): Collection
    {
        return $this->ctVisites;
    }

    public function addCtVisite(CtVisite $ctVisite): self
    {
        if (!$this->ctVisites->contains($ctVisite)) {
            $this->ctVisites[] = $ctVisite;
            $ctVisite->setCtTypeVisiteId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            // set the owning side to null (unless already changed)
            if ($ctVisite->getCtTypeVisiteId() === $this) {
                $ctVisite->setCtTypeVisiteId(null);
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
        return $this->getTpvLibelle();
    }
}
