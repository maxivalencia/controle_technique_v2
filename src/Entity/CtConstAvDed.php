<?php

namespace App\Entity;

use App\Repository\CtConstAvDedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtConstAvDedRepository::class)
 */
class CtConstAvDed
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctConstAvDeds")
     */
    private $ct_centre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctConstAvDeds")
     */
    private $ct_verificateur_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_provenance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_divers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_proprietaire_nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_proprietaire_adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cad_bon_etat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cad_sec_pers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cad_sec_march;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cad_protec_env;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_immatriculation;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $cad_date_embarquement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_lieu_embarquement;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cad_created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_observation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cad_conforme;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cad_is_active;

    /**
     * @ORM\Column(type="integer")
     */
    private $cad_genere;

    /**
     * @ORM\ManyToMany(targetEntity=CtConstAvDedCarac::class, inversedBy="ctConstAvDeds")
     */
    private $ct_const_av_ded_carac;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctConstAvDedsSec")
     */
    private $ct_user_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUtilisation::class, inversedBy="ctConstAvDeds")
     */
    private $ct_utilisation_id;

    public function __construct()
    {
        $this->ct_const_av_ded_carac = new ArrayCollection();
    }

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

    public function getCtVerificateurId(): ?CtUser
    {
        return $this->ct_verificateur_id;
    }

    public function setCtVerificateurId(?CtUser $ct_verificateur_id): self
    {
        $this->ct_verificateur_id = $ct_verificateur_id;

        return $this;
    }

    public function getCadProvenance(): ?string
    {
        return strtoupper($this->cad_provenance);
    }

    public function setCadProvenance(?string $cad_provenance): self
    {
        $this->cad_provenance = strtoupper($cad_provenance);

        return $this;
    }

    public function getCadDivers(): ?string
    {
        return $this->cad_divers;
    }

    public function setCadDivers(?string $cad_divers): self
    {
        $this->cad_divers = $cad_divers;

        return $this;
    }

    public function getCadProprietaireNom(): ?string
    {
        return $this->cad_proprietaire_nom;
    }

    public function setCadProprietaireNom(?string $cad_proprietaire_nom): self
    {
        $this->cad_proprietaire_nom = $cad_proprietaire_nom;

        return $this;
    }

    public function getCadProprietaireAdresse(): ?string
    {
        return $this->cad_proprietaire_adresse;
    }

    public function setCadProprietaireAdresse(?string $cad_proprietaire_adresse): self
    {
        $this->cad_proprietaire_adresse = $cad_proprietaire_adresse;

        return $this;
    }

    public function isCadBonEtat(): ?bool
    {
        return $this->cad_bon_etat;
    }

    public function setCadBonEtat(bool $cad_bon_etat): self
    {
        $this->cad_bon_etat = $cad_bon_etat;

        return $this;
    }

    public function isCadSecPers(): ?bool
    {
        return $this->cad_sec_pers;
    }

    public function setCadSecPers(bool $cad_sec_pers): self
    {
        $this->cad_sec_pers = $cad_sec_pers;

        return $this;
    }

    public function isCadSecMarch(): ?bool
    {
        return $this->cad_sec_march;
    }

    public function setCadSecMarch(bool $cad_sec_march): self
    {
        $this->cad_sec_march = $cad_sec_march;

        return $this;
    }

    public function isCadProtecEnv(): ?bool
    {
        return $this->cad_protec_env;
    }

    public function setCadProtecEnv(bool $cad_protec_env): self
    {
        $this->cad_protec_env = $cad_protec_env;

        return $this;
    }

    public function getCadNumero(): ?string
    {
        return strtoupper($this->cad_numero);
    }

    public function setCadNumero(?string $cad_numero): self
    {
        $this->cad_numero = strtoupper($cad_numero);

        return $this;
    }

    public function getCadImmatriculation(): ?string
    {
        return strtoupper($this->cad_immatriculation);
    }

    public function setCadImmatriculation(?string $cad_immatriculation): self
    {
        $this->cad_immatriculation = strtoupper($cad_immatriculation);

        return $this;
    }

    public function getCadDateEmbarquement(): ?\DateTimeInterface
    {
        return $this->cad_date_embarquement;
    }

    public function setCadDateEmbarquement(?\DateTimeInterface $cad_date_embarquement): self
    {
        $this->cad_date_embarquement = $cad_date_embarquement;

        return $this;
    }

    public function getCadLieuEmbarquement(): ?string
    {
        return strtoupper($this->cad_lieu_embarquement);
    }

    public function setCadLieuEmbarquement(?string $cad_lieu_embarquement): self
    {
        $this->cad_lieu_embarquement = strtoupper($cad_lieu_embarquement);

        return $this;
    }

    public function getCadCreated(): ?\DateTimeInterface
    {
        return $this->cad_created;
    }

    public function setCadCreated(\DateTimeInterface $cad_created): self
    {
        $this->cad_created = $cad_created;

        return $this;
    }

    public function getCadObservation(): ?string
    {
        return $this->cad_observation;
    }

    public function setCadObservation(?string $cad_observation): self
    {
        $this->cad_observation = $cad_observation;

        return $this;
    }

    public function isCadConforme(): ?bool
    {
        return $this->cad_conforme;
    }

    public function setCadConforme(bool $cad_conforme): self
    {
        $this->cad_conforme = $cad_conforme;

        return $this;
    }

    public function isCadIsActive(): ?bool
    {
        return $this->cad_is_active;
    }

    public function setCadIsActive(bool $cad_is_active): self
    {
        $this->cad_is_active = $cad_is_active;

        return $this;
    }

    public function getCadGenere(): ?int
    {
        return $this->cad_genere;
    }

    public function setCadGenere(int $cad_genere): self
    {
        $this->cad_genere = $cad_genere;

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDedCarac>
     */
    public function getCtConstAvDedCarac(): Collection
    {
        return $this->ct_const_av_ded_carac;
    }

    public function addCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if (!$this->ct_const_av_ded_carac->contains($ctConstAvDedCarac)) {
            $this->ct_const_av_ded_carac[] = $ctConstAvDedCarac;
        }

        return $this;
    }

    public function removeCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        $this->ct_const_av_ded_carac->removeElement($ctConstAvDedCarac);

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
        return $this->getCadNumero();
    }
}
