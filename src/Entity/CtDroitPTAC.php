<?php

namespace App\Entity;

use App\Repository\CtDroitPTACRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtDroitPTACRepository::class)
 */
class CtDroitPTAC
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtGenreCategorie::class, inversedBy="ctDroitPTACs")
     */
    private $ct_genre_categorie_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dp_prix_min;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dp_prix_max;

    /**
     * @ORM\Column(type="float")
     */
    private $dp_droit;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeDroitPTAC::class, inversedBy="ctDroitPTACs")
     */
    private $ct_type_droit_ptac_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtArretePrix::class, inversedBy="ctDroitPTACs")
     */
    private $ct_arrete_prix_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeReception::class, inversedBy="ctDroitPTACs")
     */
    private $ct_type_reception_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtGenreCategorieId(): ?CtGenreCategorie
    {
        return $this->ct_genre_categorie_id;
    }

    public function setCtGenreCategorieId(?CtGenreCategorie $ct_genre_categorie_id): self
    {
        $this->ct_genre_categorie_id = $ct_genre_categorie_id;

        return $this;
    }

    public function getDpPrixMin(): ?float
    {
        return $this->dp_prix_min;
    }

    public function setDpPrixMin(?float $dp_prix_min): self
    {
        $this->dp_prix_min = $dp_prix_min;

        return $this;
    }

    public function getDpPrixMax(): ?float
    {
        return $this->dp_prix_max;
    }

    public function setDpPrixMax(?float $dp_prix_max): self
    {
        $this->dp_prix_max = $dp_prix_max;

        return $this;
    }

    public function getDpDroit(): ?float
    {
        return $this->dp_droit;
    }

    public function setDpDroit(float $dp_droit): self
    {
        $this->dp_droit = $dp_droit;

        return $this;
    }

    public function getCtTypeDroitPtacId(): ?CtTypeDroitPTAC
    {
        return $this->ct_type_droit_ptac_id;
    }

    public function setCtTypeDroitPtacId(?CtTypeDroitPTAC $ct_type_droit_ptac_id): self
    {
        $this->ct_type_droit_ptac_id = $ct_type_droit_ptac_id;

        return $this;
    }

    public function getCtArretePrixId(): ?CtArretePrix
    {
        return $this->ct_arrete_prix_id;
    }

    public function setCtArretePrixId(?CtArretePrix $ct_arrete_prix_id): self
    {
        $this->ct_arrete_prix_id = $ct_arrete_prix_id;

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

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getCtGenreCategorieId().' '.$this->getDpDroit();
    }
}
