<?php

namespace App\Entity;

use App\Repository\CtVehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtVehiculeRepository::class)
 */
class CtVehicule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtGenre::class, inversedBy="ctVehicules")
     */
    private $ct_genre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtMarque::class, inversedBy="ctVehicules")
     */
    private $ct_marque_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_cylindre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_puissance;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_poids_vide;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_charge_utile;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_hauteur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_largeur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_longueur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vhc_num_serie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vhc_num_moteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $vhc_created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vhc_provenance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vhc_type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vhc_poids_total_charge;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="ct_vehicule_id")
     */
    private $ctCarteGrises;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_vehicule_id")
     */
    private $ctReceptions;

    public function __construct()
    {
        $this->ctCarteGrises = new ArrayCollection();
        $this->ctReceptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCtMarqueId(): ?CtMarque
    {
        return $this->ct_marque_id;
    }

    public function setCtMarqueId(?CtMarque $ct_marque_id): self
    {
        $this->ct_marque_id = $ct_marque_id;

        return $this;
    }

    public function getVhcCylindre(): ?float
    {
        return $this->vhc_cylindre;
    }

    public function setVhcCylindre(?float $vhc_cylindre): self
    {
        $this->vhc_cylindre = $vhc_cylindre;

        return $this;
    }

    public function getVhcPuissance(): ?float
    {
        return $this->vhc_puissance;
    }

    public function setVhcPuissance(?float $vhc_puissance): self
    {
        $this->vhc_puissance = $vhc_puissance;

        return $this;
    }

    public function getVhcPoidsVide(): ?float
    {
        return $this->vhc_poids_vide;
    }

    public function setVhcPoidsVide(?float $vhc_poids_vide): self
    {
        $this->vhc_poids_vide = $vhc_poids_vide;

        return $this;
    }

    public function getVhcChargeUtile(): ?float
    {
        return $this->vhc_charge_utile;
    }

    public function setVhcChargeUtile(?float $vhc_charge_utile): self
    {
        $this->vhc_charge_utile = $vhc_charge_utile;

        return $this;
    }

    public function getVhcHauteur(): ?float
    {
        return $this->vhc_hauteur;
    }

    public function setVhcHauteur(?float $vhc_hauteur): self
    {
        $this->vhc_hauteur = $vhc_hauteur;

        return $this;
    }

    public function getVhcLargeur(): ?float
    {
        return $this->vhc_largeur;
    }

    public function setVhcLargeur(?float $vhc_largeur): self
    {
        $this->vhc_largeur = $vhc_largeur;

        return $this;
    }

    public function getVhcLongueur(): ?float
    {
        return $this->vhc_longueur;
    }

    public function setVhcLongueur(?float $vhc_longueur): self
    {
        $this->vhc_longueur = $vhc_longueur;

        return $this;
    }

    public function getVhcNumSerie(): ?string
    {
        return strtoupper($this->vhc_num_serie);
    }

    public function setVhcNumSerie(?string $vhc_num_serie): self
    {
        $this->vhc_num_serie = strtoupper($vhc_num_serie);

        return $this;
    }

    public function getVhcNumMoteur(): ?string
    {
        return strtoupper($this->vhc_num_moteur);
    }

    public function setVhcNumMoteur(?string $vhc_num_moteur): self
    {
        $this->vhc_num_moteur = strtoupper($vhc_num_moteur);

        return $this;
    }

    public function getVhcCreated(): ?\DateTimeInterface
    {
        return $this->vhc_created;
    }

    public function setVhcCreated(\DateTimeInterface $vhc_created): self
    {
        $this->vhc_created = $vhc_created;

        return $this;
    }

    public function getVhcProvenance(): ?string
    {
        return strtoupper($this->vhc_provenance);
    }

    public function setVhcProvenance(?string $vhc_provenance): self
    {
        $this->vhc_provenance = strtoupper($vhc_provenance);

        return $this;
    }

    public function getVhcType(): ?string
    {
        return strtoupper($this->vhc_type);
    }

    public function setVhcType(?string $vhc_type): self
    {
        $this->vhc_type = strtoupper($vhc_type);

        return $this;
    }

    public function getVhcPoidsTotalCharge(): ?float
    {
        return $this->vhc_poids_total_charge;
    }

    public function setVhcPoidsTotalCharge(?float $vhc_poids_total_charge): self
    {
        $this->vhc_poids_total_charge = $vhc_poids_total_charge;

        return $this;
    }

    /**
     * @return Collection<int, CtCarteGrise>
     */
    public function getCtCarteGrises(): Collection
    {
        return $this->ctCarteGrises;
    }

    public function addCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if (!$this->ctCarteGrises->contains($ctCarteGrise)) {
            $this->ctCarteGrises[] = $ctCarteGrise;
            $ctCarteGrise->setCtVehiculeId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCtVehiculeId() === $this) {
                $ctCarteGrise->setCtVehiculeId(null);
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
            $ctReception->setCtVehiculeId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtVehiculeId() === $this) {
                $ctReception->setCtVehiculeId(null);
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
        return $this->getVhcNumSerie();
    }
}
