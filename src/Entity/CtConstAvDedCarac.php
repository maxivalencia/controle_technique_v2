<?php

namespace App\Entity;

use App\Repository\CtConstAvDedCaracRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtConstAvDedCaracRepository::class)
 */
class CtConstAvDedCarac
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCarrosserie::class, inversedBy="ctConstAvDedCaracs")
     */
    private $ct_carrosserie_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtConstAvDedType::class, inversedBy="ctConstAvDedCaracs")
     */
    private $ct_const_av_ded_type_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtGenre::class, inversedBy="ctConstAvDedCaracs")
     */
    private $ct_genre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtMarque::class, inversedBy="ctConstAvDedCaracs")
     */
    private $ct_marque_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtSourceEnergie::class, inversedBy="ctConstAvDedCaracs")
     */
    private $ct_source_energie_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_cylindre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_puissance;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_poids_vide;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_charge_utile;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_hauteur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_largeur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_longueur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_num_serie_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_num_moteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_type_car;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cad_poids_maxima;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cad_poids_total_charge;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cad_premiere_circule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cad_nbr_assis;

    /**
     * @ORM\ManyToMany(targetEntity=CtConstAvDed::class, mappedBy="ct_const_av_ded_carac")
     */
    private $ctConstAvDeds;

    public function __construct()
    {
        $this->ctConstAvDeds = new ArrayCollection();
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

    public function getCtConstAvDedTypeId(): ?CtConstAvDedType
    {
        return $this->ct_const_av_ded_type_id;
    }

    public function setCtConstAvDedTypeId(?CtConstAvDedType $ct_const_av_ded_type_id): self
    {
        $this->ct_const_av_ded_type_id = $ct_const_av_ded_type_id;

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

    public function getCtMarqueId(): ?CtMarque
    {
        return $this->ct_marque_id;
    }

    public function setCtMarqueId(?CtMarque $ct_marque_id): self
    {
        $this->ct_marque_id = $ct_marque_id;

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

    public function getCadCylindre(): ?float
    {
        return $this->cad_cylindre;
    }

    public function setCadCylindre(?float $cad_cylindre): self
    {
        $this->cad_cylindre = $cad_cylindre;

        return $this;
    }

    public function getCadPuissance(): ?float
    {
        return $this->cad_puissance;
    }

    public function setCadPuissance(?float $cad_puissance): self
    {
        $this->cad_puissance = $cad_puissance;

        return $this;
    }

    public function getCadPoidsVide(): ?float
    {
        return $this->cad_poids_vide;
    }

    public function setCadPoidsVide(?float $cad_poids_vide): self
    {
        $this->cad_poids_vide = $cad_poids_vide;

        return $this;
    }

    public function getCadChargeUtile(): ?float
    {
        return $this->cad_charge_utile;
    }

    public function setCadChargeUtile(?float $cad_charge_utile): self
    {
        $this->cad_charge_utile = $cad_charge_utile;

        return $this;
    }

    public function getCadHauteur(): ?float
    {
        return $this->cad_hauteur;
    }

    public function setCadHauteur(?float $cad_hauteur): self
    {
        $this->cad_hauteur = $cad_hauteur;

        return $this;
    }

    public function getCadLargeur(): ?float
    {
        return $this->cad_largeur;
    }

    public function setCadLargeur(?float $cad_largeur): self
    {
        $this->cad_largeur = $cad_largeur;

        return $this;
    }

    public function getCadLongueur(): ?float
    {
        return $this->cad_longueur;
    }

    public function setCadLongueur(?float $cad_longueur): self
    {
        $this->cad_longueur = $cad_longueur;

        return $this;
    }

    public function getCadNumSerieType(): ?string
    {
        return strtoupper($this->cad_num_serie_type);
    }

    public function setCadNumSerieType(?string $cad_num_serie_type): self
    {
        $this->cad_num_serie_type = strtoupper($cad_num_serie_type);

        return $this;
    }

    public function getCadNumMoteur(): ?string
    {
        return strtoupper($this->cad_num_moteur);
    }

    public function setCadNumMoteur(?string $cad_num_moteur): self
    {
        $this->cad_num_moteur = strtoupper($cad_num_moteur);

        return $this;
    }

    public function getCadTypeCar(): ?string
    {
        return strtoupper($this->cad_type_car);
    }

    public function setCadTypeCar(?string $cad_type_car): self
    {
        $this->cad_type_car = strtoupper($cad_type_car);

        return $this;
    }

    public function getCadPoidsMaxima(): ?string
    {
        return $this->cad_poids_maxima;
    }

    public function setCadPoidsMaxima(?string $cad_poids_maxima): self
    {
        $this->cad_poids_maxima = $cad_poids_maxima;

        return $this;
    }

    public function getCadPoidsTotalCharge(): ?float
    {
        return $this->cad_poids_total_charge;
    }

    public function setCadPoidsTotalCharge(?float $cad_poids_total_charge): self
    {
        $this->cad_poids_total_charge = $cad_poids_total_charge;

        return $this;
    }

    public function getCadPremiereCircule(): ?string
    {
        return strtoupper($this->cad_premiere_circule);
    }

    public function setCadPremiereCircule(?string $cad_premiere_circule): self
    {
        $this->cad_premiere_circule = strtoupper($cad_premiere_circule);

        return $this;
    }

    public function getCadNbrAssis(): ?int
    {
        return $this->cad_nbr_assis;
    }

    public function setCadNbrAssis(?int $cad_nbr_assis): self
    {
        $this->cad_nbr_assis = $cad_nbr_assis;

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDed>
     */
    public function getCtConstAvDeds(): Collection
    {
        return $this->ctConstAvDeds;
    }

    public function addCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if (!$this->ctConstAvDeds->contains($ctConstAvDed)) {
            $this->ctConstAvDeds[] = $ctConstAvDed;
            $ctConstAvDed->addCtConstAvDedCarac($this);
        }

        return $this;
    }

    public function removeCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if ($this->ctConstAvDeds->removeElement($ctConstAvDed)) {
            $ctConstAvDed->removeCtConstAvDedCarac($this);
        }

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getCadNumSerieType();
    }
}
