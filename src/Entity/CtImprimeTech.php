<?php

namespace App\Entity;

use App\Repository\CtImprimeTechRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtImprimeTechRepository::class)
 */
class CtImprimeTech
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctImprimeTeches")
     */
    private $ct_user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_imprime_tech;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ute_imprime_tech;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $abrev_imprime_tech;

    /**
     * @ORM\Column(type="date")
     */
    private $prtt_created_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $prtt_updated_at;

    /**
     * @ORM\OneToMany(targetEntity=CtVisiteExtraTarif::class, mappedBy="ct_imprime_tech_id")
     */
    private $ctVisiteExtraTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtBordereau::class, mappedBy="ct_imprime_tech_id")
     */
    private $ctBordereaus;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTechUse::class, mappedBy="ct_imprime_tech_id")
     */
    private $ctImprimeTechUses;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeImprime::class, inversedBy="ct_imprime_tech_id")
     */
    private $ct_type_imprime_id;

    /**
     * @ORM\ManyToMany(targetEntity=CtVisite::class, mappedBy="vst_extra")
     */
    private $ctVisites;

    public function __construct()
    {
        $this->ctVisites = new ArrayCollection();
        $this->ctVisiteExtraTarifs = new ArrayCollection();
        $this->ctBordereaus = new ArrayCollection();
        $this->ctImprimeTechUses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtUserId(): ?CtUser
    {
        return $this->ct_user_id;
    }

    public function setCtUserId(?CtUser $ct_user_id): self
    {
        $this->ct_user_id = $ct_user_id;

        return $this;
    }

    public function getNomImprimeTech(): ?string
    {
        return strtoupper($this->nom_imprime_tech);
    }

    public function setNomImprimeTech(string $nom_imprime_tech): self
    {
        $this->nom_imprime_tech = strtoupper($nom_imprime_tech);

        return $this;
    }

    public function getUteImprimeTech(): ?string
    {
        return strtoupper($this->ute_imprime_tech);
    }

    public function setUteImprimeTech(string $ute_imprime_tech): self
    {
        $this->ute_imprime_tech = strtoupper($ute_imprime_tech);

        return $this;
    }

    public function getAbrevImprimeTech(): ?string
    {
        return strtoupper($this->abrev_imprime_tech);
    }

    public function setAbrevImprimeTech(string $abrev_imprime_tech): self
    {
        $this->abrev_imprime_tech = strtoupper($abrev_imprime_tech);

        return $this;
    }

    public function getPrttCreatedAt(): ?\DateTimeInterface
    {
        return $this->prtt_created_at;
    }

    public function setPrttCreatedAt(\DateTimeInterface $prtt_created_at): self
    {
        $this->prtt_created_at = $prtt_created_at;

        return $this;
    }

    public function getPrttUpdatedAt(): ?\DateTimeInterface
    {
        return $this->prtt_updated_at;
    }

    public function setPrttUpdatedAt(?\DateTimeInterface $prtt_updated_at): self
    {
        $this->prtt_updated_at = $prtt_updated_at;

        return $this;
    }

    /**
     * @return Collection<int, CtVisiteExtraTarif>
     */
    public function getCtVisiteExtraTarifs(): Collection
    {
        return $this->ctVisiteExtraTarifs;
    }

    public function addCtVisiteExtraTarif(CtVisiteExtraTarif $ctVisiteExtraTarif): self
    {
        if (!$this->ctVisiteExtraTarifs->contains($ctVisiteExtraTarif)) {
            $this->ctVisiteExtraTarifs[] = $ctVisiteExtraTarif;
            $ctVisiteExtraTarif->setCtImprimeTechId($this);
        }

        return $this;
    }

    public function removeCtVisiteExtraTarif(CtVisiteExtraTarif $ctVisiteExtraTarif): self
    {
        if ($this->ctVisiteExtraTarifs->removeElement($ctVisiteExtraTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctVisiteExtraTarif->getCtImprimeTechId() === $this) {
                $ctVisiteExtraTarif->setCtImprimeTechId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtBordereau>
     */
    public function getCtBordereaus(): Collection
    {
        return $this->ctBordereaus;
    }

    public function addCtBordereau(CtBordereau $ctBordereau): self
    {
        if (!$this->ctBordereaus->contains($ctBordereau)) {
            $this->ctBordereaus[] = $ctBordereau;
            $ctBordereau->setCtImprimeTechId($this);
        }

        return $this;
    }

    public function removeCtBordereau(CtBordereau $ctBordereau): self
    {
        if ($this->ctBordereaus->removeElement($ctBordereau)) {
            // set the owning side to null (unless already changed)
            if ($ctBordereau->getCtImprimeTechId() === $this) {
                $ctBordereau->setCtImprimeTechId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtImprimeTechUse>
     */
    public function getCtImprimeTechUses(): Collection
    {
        return $this->ctImprimeTechUses;
    }

    public function addCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if (!$this->ctImprimeTechUses->contains($ctImprimeTechUse)) {
            $this->ctImprimeTechUses[] = $ctImprimeTechUse;
            $ctImprimeTechUse->setCtImprimeTechId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if ($this->ctImprimeTechUses->removeElement($ctImprimeTechUse)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTechUse->getCtImprimeTechId() === $this) {
                $ctImprimeTechUse->setCtImprimeTechId(null);
            }
        }

        return $this;
    }

    public function getCtTypeImprimeId(): ?CtTypeImprime
    {
        return $this->ct_type_imprime_id;
    }

    public function setCtTypeImprimeId(?CtTypeImprime $ct_type_imprime_id): self
    {
        $this->ct_type_imprime_id = $ct_type_imprime_id;

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
            $ctVisite->addVstImprimeTechId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            $ctVisite->removeVstImprimeTechId($this);
        }

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getAbrevImprimeTech();
    }
}
