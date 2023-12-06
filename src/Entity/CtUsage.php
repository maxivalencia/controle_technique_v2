<?php

namespace App\Entity;

use App\Repository\CtUsageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtUsageRepository::class)
 */
class CtUsage
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
    private $usg_libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $usg_validite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $usg_created;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeUsage::class, inversedBy="ctUsages")
     */
    private $ct_type_usage_id;

    /**
     * @ORM\OneToMany(targetEntity=CtUsageTarif::class, mappedBy="ct_usage_id")
     */
    private $ctUsageTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_usage_id")
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

    public function getUsgLibelle(): ?string
    {
        return strtoupper($this->usg_libelle);
    }

    public function setUsgLibelle(string $usg_libelle): self
    {
        $this->usg_libelle = strtoupper($usg_libelle);

        return $this;
    }

    public function getUsgValidite(): ?int
    {
        return $this->usg_validite;
    }

    public function setUsgValidite(int $usg_validite): self
    {
        $this->usg_validite = $usg_validite;

        return $this;
    }

    public function getUsgCreated(): ?\DateTimeInterface
    {
        return $this->usg_created;
    }

    public function setUsgCreated(?\DateTimeInterface $usg_created): self
    {
        $this->usg_created = $usg_created;

        return $this;
    }

    public function getCtTypeUsageId(): ?CtTypeUsage
    {
        return $this->ct_type_usage_id;
    }

    public function setCtTypeUsageId(?CtTypeUsage $ct_type_usage_id): self
    {
        $this->ct_type_usage_id = $ct_type_usage_id;

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
            $ctUsageTarif->setCtUsageId($this);
        }

        return $this;
    }

    public function removeCtUsageTarif(CtUsageTarif $ctUsageTarif): self
    {
        if ($this->ctUsageTarifs->removeElement($ctUsageTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctUsageTarif->getCtUsageId() === $this) {
                $ctUsageTarif->setCtUsageId(null);
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
            $ctVisite->setCtUsageId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            // set the owning side to null (unless already changed)
            if ($ctVisite->getCtUsageId() === $this) {
                $ctVisite->setCtUsageId(null);
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
        return $this->getUsgLibelle();
    }
}
