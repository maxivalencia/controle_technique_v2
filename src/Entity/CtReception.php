<?php

namespace App\Entity;

use App\Repository\CtReceptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtReceptionRepository::class)
 */
class CtReception
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctReceptions")
     */
    private $ct_centre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtMotif::class, inversedBy="ctReceptions")
     */
    private $ct_motif_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeReception::class, inversedBy="ctReceptions")
     */
    private $ct_type_reception_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctReceptions")
     */
    private $ct_user_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctReceptions")
     */
    private $ct_verificateur_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUtilisation::class, inversedBy="ctReceptions")
     */
    private $ct_utilisation_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtVehicule::class, inversedBy="ctReceptions")
     */
    private $ct_vehicule_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $rcp_mise_service;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_immatriculation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_proprietaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rcp_nbr_assis;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rcp_ngr_debout;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_num_pv;

    /**
     * @ORM\ManyToOne(targetEntity=CtSourceEnergie::class, inversedBy="ctReceptions")
     */
    private $ct_source_energie_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCarrosserie::class, inversedBy="ctReceptions")
     */
    private $ct_carrosserie_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_num_group;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rcp_created;

    /**
     * @ORM\ManyToOne(targetEntity=CtGenre::class, inversedBy="ctReceptions")
     */
    private $ct_genre_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rcp_is_active;

    /**
     * @ORM\Column(type="integer")
     */
    private $rcp_genere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcp_observation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCtMotifId(): ?CtMotif
    {
        return $this->ct_motif_id;
    }

    public function setCtMotifId(?CtMotif $ct_motif_id): self
    {
        $this->ct_motif_id = $ct_motif_id;

        return $this;
    }

    public function getCtTypeReceptionId(): ?CtTypeReception
    {
        return $this->ct_type_reception_id;
    }

    public function setCtTypeReceptionId(?CtTypeReception $ct_type_reception_id): self
    {
        $this->ct_type_reception_id = $ct_type_reception_id;

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

    public function getCtUtilisationId(): ?CtUtilisation
    {
        return $this->ct_utilisation_id;
    }

    public function setCtUtilisationId(?CtUtilisation $ct_utilisation_id): self
    {
        $this->ct_utilisation_id = $ct_utilisation_id;

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

    public function getRcpMiseService(): ?\DateTimeInterface
    {
        return $this->rcp_mise_service;
    }

    public function setRcpMiseService(?\DateTimeInterface $rcp_mise_service): self
    {
        $this->rcp_mise_service = $rcp_mise_service;

        return $this;
    }

    public function getRcpImmatriculation(): ?string
    {
        return strtoupper($this->rcp_immatriculation);
    }

    public function setRcpImmatriculation(?string $rcp_immatriculation): self
    {
        $this->rcp_immatriculation = strtoupper($rcp_immatriculation);

        return $this;
    }

    public function getRcpProprietaire(): ?string
    {
        return $this->rcp_proprietaire;
    }

    public function setRcpProprietaire(?string $rcp_proprietaire): self
    {
        $this->rcp_proprietaire = $rcp_proprietaire;

        return $this;
    }

    public function getRcpProfession(): ?string
    {
        return $this->rcp_profession;
    }

    public function setRcpProfession(?string $rcp_profession): self
    {
        $this->rcp_profession = $rcp_profession;

        return $this;
    }

    public function getRcpAdresse(): ?string
    {
        return $this->rcp_adresse;
    }

    public function setRcpAdresse(?string $rcp_adresse): self
    {
        $this->rcp_adresse = $rcp_adresse;

        return $this;
    }

    public function getRcpNbrAssis(): ?int
    {
        return $this->rcp_nbr_assis;
    }

    public function setRcpNbrAssis(?int $rcp_nbr_assis): self
    {
        $this->rcp_nbr_assis = $rcp_nbr_assis;

        return $this;
    }

    public function getRcpNgrDebout(): ?int
    {
        return $this->rcp_ngr_debout;
    }

    public function setRcpNgrDebout(?int $rcp_ngr_debout): self
    {
        $this->rcp_ngr_debout = $rcp_ngr_debout;

        return $this;
    }

    public function getRcpNumPv(): ?string
    {
        return strtoupper($this->rcp_num_pv);
    }

    public function setRcpNumPv(?string $rcp_num_pv): self
    {
        $this->rcp_num_pv = strtoupper($rcp_num_pv);

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

    public function getCtCarrosserieId(): ?CtCarrosserie
    {
        return $this->ct_carrosserie_id;
    }

    public function setCtCarrosserieId(?CtCarrosserie $ct_carrosserie_id): self
    {
        $this->ct_carrosserie_id = $ct_carrosserie_id;

        return $this;
    }

    public function getRcpNumGroup(): ?string
    {
        return strtoupper($this->rcp_num_group);
    }

    public function setRcpNumGroup(?string $rcp_num_group): self
    {
        $this->rcp_num_group = strtoupper($rcp_num_group);

        return $this;
    }

    public function getRcpCreated(): ?\DateTimeInterface
    {
        return $this->rcp_created;
    }

    public function setRcpCreated(?\DateTimeInterface $rcp_created): self
    {
        $this->rcp_created = $rcp_created;

        return $this;
    }

    public function getCtGenreId(): ?CtGenre
    {
        return $this->ct_genre_id;
    }

    public function setCtGenreId(?CtGenre $ct_genre_id): self
    {
        $this->ct_genre_id = $ct_genre_id;

        return $this;
    }

    public function isRcpIsActive(): ?bool
    {
        return $this->rcp_is_active;
    }

    public function setRcpIsActive(bool $rcp_is_active): self
    {
        $this->rcp_is_active = $rcp_is_active;

        return $this;
    }

    public function getRcpGenere(): ?int
    {
        return $this->rcp_genere;
    }

    public function setRcpGenere(int $rcp_genere): self
    {
        $this->rcp_genere = $rcp_genere;

        return $this;
    }

    public function getRcpObservation(): ?string
    {
        return $this->rcp_observation;
    }

    public function setRcpObservation(?string $rcp_observation): self
    {
        $this->rcp_observation = $rcp_observation;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getRcpNumPv();
    }
}
