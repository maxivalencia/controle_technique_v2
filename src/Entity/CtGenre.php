<?php

namespace App\Entity;

use App\Repository\CtGenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtGenreRepository::class)
 */
class CtGenre
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
    private $gr_libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gr_code;

    /**
     * @ORM\ManyToOne(targetEntity=CtGenreCategorie::class, inversedBy="ctGenres")
     */
    private $ct_genre_categorie_id;

    /**
     * @ORM\OneToMany(targetEntity=CtGenreTarif::class, mappedBy="ct_genre_id")
     */
    private $ctGenreTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtVehicule::class, mappedBy="ct_genre_id")
     */
    private $ctVehicules;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDedCarac::class, mappedBy="ct_genre_id")
     */
    private $ctConstAvDedCaracs;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_genre_id")
     */
    private $ctReceptions;

    public function __construct()
    {
        $this->ctGenreTarifs = new ArrayCollection();
        $this->ctVehicules = new ArrayCollection();
        $this->ctConstAvDedCaracs = new ArrayCollection();
        $this->ctReceptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrLibelle(): ?string
    {
        return strtoupper($this->gr_libelle);
    }

    public function setGrLibelle(string $gr_libelle): self
    {
        $this->gr_libelle = strtoupper($gr_libelle);

        return $this;
    }

    public function getGrCode(): ?string
    {
        return strtoupper($this->gr_code);
    }

    public function setGrCode(string $gr_code): self
    {
        $this->gr_code = strtoupper($gr_code);

        return $this;
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

    /**
     * @return Collection<int, CtGenreTarif>
     */
    public function getCtGenreTarifs(): Collection
    {
        return $this->ctGenreTarifs;
    }

    public function addCtGenreTarif(CtGenreTarif $ctGenreTarif): self
    {
        if (!$this->ctGenreTarifs->contains($ctGenreTarif)) {
            $this->ctGenreTarifs[] = $ctGenreTarif;
            $ctGenreTarif->setCtGenreId($this);
        }

        return $this;
    }

    public function removeCtGenreTarif(CtGenreTarif $ctGenreTarif): self
    {
        if ($this->ctGenreTarifs->removeElement($ctGenreTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctGenreTarif->getCtGenreId() === $this) {
                $ctGenreTarif->setCtGenreId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtVehicule>
     */
    public function getCtVehicules(): Collection
    {
        return $this->ctVehicules;
    }

    public function addCtVehicule(CtVehicule $ctVehicule): self
    {
        if (!$this->ctVehicules->contains($ctVehicule)) {
            $this->ctVehicules[] = $ctVehicule;
            $ctVehicule->setCtGenreId($this);
        }

        return $this;
    }

    public function removeCtVehicule(CtVehicule $ctVehicule): self
    {
        if ($this->ctVehicules->removeElement($ctVehicule)) {
            // set the owning side to null (unless already changed)
            if ($ctVehicule->getCtGenreId() === $this) {
                $ctVehicule->setCtGenreId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDedCarac>
     */
    public function getCtConstAvDedCaracs(): Collection
    {
        return $this->ctConstAvDedCaracs;
    }

    public function addCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if (!$this->ctConstAvDedCaracs->contains($ctConstAvDedCarac)) {
            $this->ctConstAvDedCaracs[] = $ctConstAvDedCarac;
            $ctConstAvDedCarac->setCtGenreId($this);
        }

        return $this;
    }

    public function removeCtConstAvDedCarac(CtConstAvDedCarac $ctConstAvDedCarac): self
    {
        if ($this->ctConstAvDedCaracs->removeElement($ctConstAvDedCarac)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDedCarac->getCtGenreId() === $this) {
                $ctConstAvDedCarac->setCtGenreId(null);
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
            $ctReception->setCtGenreId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtGenreId() === $this) {
                $ctReception->setCtGenreId(null);
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
        return $this->getGrLibelle();
    }
}
