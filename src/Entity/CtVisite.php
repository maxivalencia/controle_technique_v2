<?php

namespace App\Entity;

use App\Repository\CtVisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtVisiteRepository::class)
 */
class CtVisite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCarteGrise::class, inversedBy="ctVisites")
     */
    private $ct_carte_grise_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctVisites")
     */
    private $ct_centre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeVisite::class, inversedBy="ctVisites")
     */
    private $ct_type_visite_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUsage::class, inversedBy="ctVisites")
     */
    private $ct_usage_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctVisites")
     */
    private $ct_user_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctVisites")
     */
    private $ct_verificateur_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vst_num_pv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vst_num_feuille_caisse;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $vst_date_expiration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $vst_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $vst_updated;

    /**
     * @ORM\ManyToOne(targetEntity=CtUtilisation::class, inversedBy="ctVisites")
     */
    private $ct_utilisation_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vst_is_apte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vst_is_contre_visite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vst_duree_reparation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vst_is_active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vst_genere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vst_observation;

    /**
     * @ORM\ManyToMany(targetEntity=CtAnomalie::class, inversedBy="ctVisites", cascade={"persist", "remove"})
     */
    private $vst_anomalie_id;

    /**
     * @ORM\ManyToMany(targetEntity=CtVisiteExtra::class, inversedBy="ctVisites", cascade={"persist", "remove"})
     */
    private $vst_extra;

    /**
     * @ORM\OneToMany(targetEntity=CtExtraVente::class, mappedBy="ct_visite_id")
     */
    private $ctExtraVentes;

    /**
     * @ORM\ManyToMany(targetEntity=CtImprimeTech::class, inversedBy="ctVisites", cascade={"persist", "remove"})
     */
    private $vst_imprime_tech_id;

    public function __construct()
    {
        $this->vst_anomalie_id = new ArrayCollection();
        $this->vst_extra = new ArrayCollection();
        $this->ctExtraVentes = new ArrayCollection();
        $this->vst_imprime_tech_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtCarteGriseId(): ?CtCarteGrise
    {
        return $this->ct_carte_grise_id;
    }

    public function setCtCarteGriseId(?CtCarteGrise $ct_carte_grise_id): self
    {
        $this->ct_carte_grise_id = $ct_carte_grise_id;

        return $this;
    }

    public function getCtCentreId(): ?CtCentre
    {
        return $this->ct_centre_id;
    }

    public function setCtCentreId(?CtCentre $ct_centre_id): self
    {
        $this->ct_centre_id = $ct_centre_id;

        return $this;
    }

    public function getCtTypeVisiteId(): ?CtTypeVisite
    {
        return $this->ct_type_visite_id;
    }

    public function setCtTypeVisiteId(?CtTypeVisite $ct_type_visite_id): self
    {
        $this->ct_type_visite_id = $ct_type_visite_id;

        return $this;
    }

    public function getCtUsageId(): ?CtUsage
    {
        return $this->ct_usage_id;
    }

    public function setCtUsageId(?CtUsage $ct_usage_id): self
    {
        $this->ct_usage_id = $ct_usage_id;

        return $this;
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

    public function getCtVerificateurId(): ?CtUser
    {
        return $this->ct_verificateur_id;
    }

    public function setCtVerificateurId(?CtUser $ct_verificateur_id): self
    {
        $this->ct_verificateur_id = $ct_verificateur_id;

        return $this;
    }

    public function getVstNumPv(): ?string
    {
        return strtoupper($this->vst_num_pv);
    }

    public function setVstNumPv(?string $vst_num_pv): self
    {
        $this->vst_num_pv = strtoupper($vst_num_pv);

        return $this;
    }

    public function getVstNumFeuilleCaisse(): ?string
    {
        return strtoupper($this->vst_num_feuille_caisse);
    }

    public function setVstNumFeuilleCaisse(?string $vst_num_feuille_caisse): self
    {
        $this->vst_num_feuille_caisse = strtoupper($vst_num_feuille_caisse);

        return $this;
    }

    public function getVstDateExpiration(): ?\DateTimeInterface
    {
        return $this->vst_date_expiration;
    }

    public function setVstDateExpiration(?\DateTimeInterface $vst_date_expiration): self
    {
        $this->vst_date_expiration = $vst_date_expiration;

        return $this;
    }

    public function getVstCreated(): ?\DateTimeInterface
    {
        return $this->vst_created;
    }

    public function setVstCreated(?\DateTimeInterface $vst_created): self
    {
        $this->vst_created = $vst_created;

        return $this;
    }

    public function getVstUpdated(): ?\DateTimeInterface
    {
        return $this->vst_updated;
    }

    public function setVstUpdated(?\DateTimeInterface $vst_updated): self
    {
        $this->vst_updated = $vst_updated;

        return $this;
    }

    public function getCtUtilisationId(): ?CtUtilisation
    {
        return $this->ct_utilisation_id;
    }

    public function setCtUtilisationId(?CtUtilisation $ct_utilisation_id): self
    {
        $this->ct_utilisation_id = $ct_utilisation_id;

        return $this;
    }

    public function isVstIsApte(): ?bool
    {
        return $this->vst_is_apte;
    }

    public function setVstIsApte(bool $vst_is_apte): self
    {
        $this->vst_is_apte = $vst_is_apte;

        return $this;
    }

    public function isVstIsContreVisite(): ?bool
    {
        return $this->vst_is_contre_visite;
    }

    public function setVstIsContreVisite(bool $vst_is_contre_visite): self
    {
        $this->vst_is_contre_visite = $vst_is_contre_visite;

        return $this;
    }

    public function getVstDureeReparation(): ?string
    {
        return strtoupper($this->vst_duree_reparation);
    }

    public function setVstDureeReparation(?string $vst_duree_reparation): self
    {
        $this->vst_duree_reparation = strtoupper($vst_duree_reparation);

        return $this;
    }

    public function isVstIsActive(): ?bool
    {
        return $this->vst_is_active;
    }

    public function setVstIsActive(bool $vst_is_active): self
    {
        $this->vst_is_active = $vst_is_active;

        return $this;
    }

    public function getVstGenere(): ?int
    {
        return $this->vst_genere;
    }

    public function setVstGenere(?int $vst_genere): self
    {
        $this->vst_genere = $vst_genere;

        return $this;
    }

    public function getVstObservation(): ?string
    {
        return $this->vst_observation;
    }

    public function setVstObservation(?string $vst_observation): self
    {
        $this->vst_observation = $vst_observation;

        return $this;
    }

    /**
     * @return Collection<int, CtAnomalie>
     */
    public function getVstAnomalieId(): Collection
    {
        return $this->vst_anomalie_id;
    }

    public function addVstAnomalieId(CtAnomalie $vstAnomalieId): self
    {
        if (!$this->vst_anomalie_id->contains($vstAnomalieId)) {
            $this->vst_anomalie_id[] = $vstAnomalieId;
        }

        return $this;
    }

    public function removeVstAnomalieId(CtAnomalie $vstAnomalieId): self
    {
        $this->vst_anomalie_id->removeElement($vstAnomalieId);

        return $this;
    }

    /**
     * @return Collection<int, CtVisiteExtra>
     */
    public function getVstExtra(): Collection
    {
        return $this->vst_extra;
    }

    public function addVstExtra(CtVisiteExtra $vstExtra): self
    {
        if (!$this->vst_extra->contains($vstExtra)) {
            $this->vst_extra[] = $vstExtra;
        }

        return $this;
    }

    public function removeVstExtra(CtVisiteExtra $vstExtra): self
    {
        $this->vst_extra->removeElement($vstExtra);

        return $this;
    }

    /**
     * @return Collection<int, CtImprimeTech>
     */
    public function getVstImprimeTechId(): Collection
    {
        return $this->vst_imprime_tech_id;
    }

    public function addVstImprimeTechId(CtImprimeTech $vst_imprime_tech_id): self
    {
        if (!$this->vst_imprime_tech_id->contains($vst_imprime_tech_id)) {
            $this->vst_imprime_tech_id[] = $vst_imprime_tech_id;
        }

        return $this;
    }

    public function removeVstImprimeTechId(CtImprimeTech $vst_imprime_tech_id): self
    {
        $this->vst_imprime_tech_id->removeElement($vst_imprime_tech_id);

        return $this;
    }

    /**
     * @return Collection<int, CtExtraVente>
     */
    public function getCtExtraVentes(): Collection
    {
        return $this->ctExtraVentes;
    }

    public function addCtExtraVente(CtExtraVente $ctExtraVente): self
    {
        if (!$this->ctExtraVentes->contains($ctExtraVente)) {
            $this->ctExtraVentes[] = $ctExtraVente;
            $ctExtraVente->setCtVisiteId($this);
        }

        return $this;
    }

    public function removeCtExtraVente(CtExtraVente $ctExtraVente): self
    {
        if ($this->ctExtraVentes->removeElement($ctExtraVente)) {
            // set the owning side to null (unless already changed)
            if ($ctExtraVente->getCtVisiteId() === $this) {
                $ctExtraVente->setCtVisiteId(null);
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
        return $this->getVstNumPv();
    }
}
