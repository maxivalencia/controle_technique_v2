<?php

namespace App\Entity;

use App\Repository\CtCarteGriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtCarteGriseRepository::class)
 */
class CtCarteGrise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCarrosserie::class, inversedBy="ctCarteGrises")
     */
    private $ct_carrosserie_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctCarteGrises")
     */
    private $ct_centre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtSourceEnergie::class, inversedBy="ctCarteGrises")
     */
    private $ct_source_energie_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtVehicule::class, inversedBy="ctCarteGrises")
     */
    private $ct_vehicule_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctCarteGrises")
     */
    private $ct_user_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $cg_date_emission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_phone;

    /**
     * @ORM\Column(type="integer")
     */
    private $cg_nbr_assis;

    /**
     * @ORM\Column(type="integer")
     */
    private $cg_nbr_debout;

    /**
     * @ORM\Column(type="integer")
     */
    private $cg_puissance_admin;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $cg_mise_en_service;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_patente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_ani;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_rta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_num_carte_violette;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $cg_date_carte_violette;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_lieu_carte_violette;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_num_vignette;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $cg_date_vignette;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_lieu_vignette;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cg_immatriculation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cg_created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_nom_cooperative;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_itineraire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cg_is_transport;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_num_identification;

    /**
     * @ORM\ManyToOne(targetEntity=CtZoneDesserte::class, inversedBy="ctCarteGrises")
     */
    private $ct_zone_desserte_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cg_is_active;

    /**
     * @ORM\ManyToOne(targetEntity=CtCarteGrise::class, inversedBy="ctCarteGrises")
     */
    private $cg_antecedant_id;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="cg_antecedant_id")
     */
    private $ctCarteGrises;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_observation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cg_commune;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_carte_grise_id")
     */
    private $ctVisites;

    /**
     * @ORM\OneToMany(targetEntity=CtAutreVente::class, mappedBy="ct_carte_grise_id")
     */
    private $ctAutreVentes;

    /**
     * @ORM\ManyToOne(targetEntity=CtUtilisation::class, inversedBy="ctVisites")
     */
    private $ct_utilisation_id;

    public function __construct()
    {
        $this->ctCarteGrises = new ArrayCollection();
        $this->ctVisites = new ArrayCollection();
        $this->ctAutreVentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtCarrosserieId(): ?CtCarrosserie
    {
        return $this->ct_carrosserie_id;
    }

    public function setCtCarrosserieId(?CtCarrosserie $ct_carrosserie_id): self
    {
        $this->ct_carrosserie_id = $ct_carrosserie_id;

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

    public function getCtSourceEnergieId(): ?CtSourceEnergie
    {
        return $this->ct_source_energie_id;
    }

    public function setCtSourceEnergieId(?CtSourceEnergie $ct_source_energie_id): self
    {
        $this->ct_source_energie_id = $ct_source_energie_id;

        return $this;
    }

    public function getCtVehiculeId(): ?CtVehicule
    {
        return $this->ct_vehicule_id;
    }

    public function setCtVehiculeId(?CtVehicule $ct_vehicule_id): self
    {
        $this->ct_vehicule_id = $ct_vehicule_id;

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

    public function getCgDateEmission(): ?\DateTimeInterface
    {
        return $this->cg_date_emission;
    }

    public function setCgDateEmission(?\DateTimeInterface $cg_date_emission): self
    {
        $this->cg_date_emission = $cg_date_emission;

        return $this;
    }

    public function getCgNom(): ?string
    {
        return $this->cg_nom;
    }

    public function setCgNom(?string $cg_nom): self
    {
        $this->cg_nom = $cg_nom;

        return $this;
    }

    public function getCgPrenom(): ?string
    {
        return $this->cg_prenom;
    }

    public function setCgPrenom(?string $cg_prenom): self
    {
        $this->cg_prenom = $cg_prenom;

        return $this;
    }

    public function getCgProfession(): ?string
    {
        return $this->cg_profession;
    }

    public function setCgProfession(?string $cg_profession): self
    {
        $this->cg_profession = $cg_profession;

        return $this;
    }

    public function getCgAdresse(): ?string
    {
        return $this->cg_adresse;
    }

    public function setCgAdresse(?string $cg_adresse): self
    {
        $this->cg_adresse = $cg_adresse;

        return $this;
    }

    public function getCgPhone(): ?string
    {
        return strtoupper($this->cg_phone);
    }

    public function setCgPhone(?string $cg_phone): self
    {
        $this->cg_phone = strtoupper($cg_phone);

        return $this;
    }

    public function getCgNbrAssis(): ?int
    {
        return $this->cg_nbr_assis;
    }

    public function setCgNbrAssis(int $cg_nbr_assis): self
    {
        $this->cg_nbr_assis = $cg_nbr_assis;

        return $this;
    }

    public function getCgNbrDebout(): ?int
    {
        return $this->cg_nbr_debout;
    }

    public function setCgNbrDebout(int $cg_nbr_debout): self
    {
        $this->cg_nbr_debout = $cg_nbr_debout;

        return $this;
    }

    public function getCgPuissanceAdmin(): ?int
    {
        return $this->cg_puissance_admin;
    }

    public function setCgPuissanceAdmin(int $cg_puissance_admin): self
    {
        $this->cg_puissance_admin = $cg_puissance_admin;

        return $this;
    }

    public function getCgMiseEnService(): ?\DateTimeInterface
    {
        return $this->cg_mise_en_service;
    }

    public function setCgMiseEnService(?\DateTimeInterface $cg_mise_en_service): self
    {
        $this->cg_mise_en_service = $cg_mise_en_service;

        return $this;
    }

    public function getCgPatente(): ?string
    {
        return strtoupper($this->cg_patente);
    }

    public function setCgPatente(?string $cg_patente): self
    {
        $this->cg_patente = strtoupper($cg_patente);

        return $this;
    }

    public function getCgAni(): ?string
    {
        return $this->cg_ani;
    }

    public function setCgAni(?string $cg_ani): self
    {
        $this->cg_ani = $cg_ani;

        return $this;
    }

    public function getCgRta(): ?string
    {
        return $this->cg_rta;
    }

    public function setCgRta(?string $cg_rta): self
    {
        $this->cg_rta = $cg_rta;

        return $this;
    }

    public function getCgNumCarteViolette(): ?string
    {
        return strtoupper($this->cg_num_carte_violette);
    }

    public function setCgNumCarteViolette(?string $cg_num_carte_violette): self
    {
        $this->cg_num_carte_violette = strtoupper($cg_num_carte_violette);

        return $this;
    }

    public function getCgDateCarteViolette(): ?\DateTimeInterface
    {
        return $this->cg_date_carte_violette;
    }

    public function setCgDateCarteViolette(?\DateTimeInterface $cg_date_carte_violette): self
    {
        $this->cg_date_carte_violette = $cg_date_carte_violette;

        return $this;
    }

    public function getCgLieuCarteViolette(): ?string
    {
        return strtoupper($this->cg_lieu_carte_violette);
    }

    public function setCgLieuCarteViolette(?string $cg_lieu_carte_violette): self
    {
        $this->cg_lieu_carte_violette = strtoupper($cg_lieu_carte_violette);

        return $this;
    }

    public function getCgNumVignette(): ?string
    {
        return strtoupper($this->cg_num_vignette);
    }

    public function setCgNumVignette(?string $cg_num_vignette): self
    {
        $this->cg_num_vignette = strtoupper($cg_num_vignette);

        return $this;
    }

    public function getCgDateVignette(): ?\DateTimeInterface
    {
        return $this->cg_date_vignette;
    }

    public function setCgDateVignette(?\DateTimeInterface $cg_date_vignette): self
    {
        $this->cg_date_vignette = $cg_date_vignette;

        return $this;
    }

    public function getCgLieuVignette(): ?string
    {
        return strtoupper($this->cg_lieu_vignette);
    }

    public function setCgLieuVignette(?string $cg_lieu_vignette): self
    {
        $this->cg_lieu_vignette = strtoupper($cg_lieu_vignette);

        return $this;
    }

    public function getCgImmatriculation(): ?string
    {
        return strtoupper($this->cg_immatriculation);
    }

    public function setCgImmatriculation(string $cg_immatriculation): self
    {
        $this->cg_immatriculation = strtoupper($cg_immatriculation);

        return $this;
    }

    public function getCgCreated(): ?\DateTimeInterface
    {
        return $this->cg_created;
    }

    public function setCgCreated(\DateTimeInterface $cg_created): self
    {
        $this->cg_created = $cg_created;

        return $this;
    }

    public function getCgNomCooperative(): ?string
    {
        return strtoupper($this->cg_nom_cooperative);
    }

    public function setCgNomCooperative(?string $cg_nom_cooperative): self
    {
        $this->cg_nom_cooperative = strtoupper($cg_nom_cooperative);

        return $this;
    }

    public function getCgItineraire(): ?string
    {
        return strtoupper($this->cg_itineraire);
    }

    public function setCgItineraire(?string $cg_itineraire): self
    {
        $this->cg_itineraire = strtoupper($cg_itineraire);

        return $this;
    }

    public function isCgIsTransport(): ?bool
    {
        return $this->cg_is_transport;
    }

    public function setCgIsTransport(bool $cg_is_transport): self
    {
        $this->cg_is_transport = $cg_is_transport;

        return $this;
    }

    public function getCgNumIdentification(): ?string
    {
        return strtoupper($this->cg_num_identification);
    }

    public function setCgNumIdentification(?string $cg_num_identification): self
    {
        $this->cg_num_identification = strtoupper($cg_num_identification);

        return $this;
    }

    public function getCtZoneDesserteId(): ?CtZoneDesserte
    {
        return $this->ct_zone_desserte_id;
    }

    public function setCtZoneDesserteId(?CtZoneDesserte $ct_zone_desserte_id): self
    {
        $this->ct_zone_desserte_id = $ct_zone_desserte_id;

        return $this;
    }

    public function isCgIsActive(): ?bool
    {
        return $this->cg_is_active;
    }

    public function setCgIsActive(bool $cg_is_active): self
    {
        $this->cg_is_active = $cg_is_active;

        return $this;
    }

    public function getCgAntecedantId(): ?self
    {
        return $this->cg_antecedant_id;
    }

    public function setCgAntecedantId(?self $cg_antecedant_id): self
    {
        $this->cg_antecedant_id = $cg_antecedant_id;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCtCarteGrises(): Collection
    {
        return $this->ctCarteGrises;
    }

    public function addCtCarteGrise(self $ctCarteGrise): self
    {
        if (!$this->ctCarteGrises->contains($ctCarteGrise)) {
            $this->ctCarteGrises[] = $ctCarteGrise;
            $ctCarteGrise->setCgAntecedantId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(self $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCgAntecedantId() === $this) {
                $ctCarteGrise->setCgAntecedantId(null);
            }
        }

        return $this;
    }

    public function getCgObservation(): ?string
    {
        return $this->cg_observation;
    }

    public function setCgObservation(?string $cg_observation): self
    {
        $this->cg_observation = $cg_observation;

        return $this;
    }

    public function getCgCommune(): ?string
    {
        return strtoupper($this->cg_commune);
    }

    public function setCgCommune(?string $cg_commune): self
    {
        $this->cg_commune = strtoupper($cg_commune);

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
            $ctVisite->setCtCarteGriseId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            // set the owning side to null (unless already changed)
            if ($ctVisite->getCtCarteGriseId() === $this) {
                $ctVisite->setCtCarteGriseId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtAutreVente>
     */
    public function getCtAutreVentes(): Collection
    {
        return $this->ctAutreVentes;
    }

    public function addCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if (!$this->ctAutreVentes->contains($ctAutreVente)) {
            $this->ctAutreVentes[] = $ctAutreVente;
            $ctAutreVente->setCtCarteGriseId($this);
        }

        return $this;
    }

    public function removeCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreVente->getCtCarteGriseId() === $this) {
                $ctAutreVente->setCtCarteGriseId(null);
            }
        }

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

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getCgImmatriculation().' '.$this->getCgNom().' '.$this->getCgPrenom();
    }
}
