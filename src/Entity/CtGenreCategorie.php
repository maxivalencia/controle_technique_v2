<?php

namespace App\Entity;

use App\Repository\CtGenreCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtGenreCategorieRepository::class)
 */
class CtGenreCategorie
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
    private $gc_libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gc_is_calculable;

    /**
     * @ORM\OneToMany(targetEntity=CtGenre::class, mappedBy="ct_genre_categorie_id")
     */
    private $ctGenres;

    /**
     * @ORM\OneToMany(targetEntity=CtDroitPTAC::class, mappedBy="ct_genre_categorie_id")
     */
    private $ctDroitPTACs;

    public function __construct()
    {
        $this->ctGenres = new ArrayCollection();
        $this->ctDroitPTACs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGcLibelle(): ?string
    {
        return strtoupper($this->gc_libelle);
    }

    public function setGcLibelle(string $gc_libelle): self
    {
        $this->gc_libelle = strtoupper($gc_libelle);

        return $this;
    }

    public function isGcIsCalculable(): ?bool
    {
        return $this->gc_is_calculable;
    }

    public function setGcIsCalculable(bool $gc_is_calculable): self
    {
        $this->gc_is_calculable = $gc_is_calculable;

        return $this;
    }

    /**
     * @return Collection<int, CtGenre>
     */
    public function getCtGenres(): Collection
    {
        return $this->ctGenres;
    }

    public function addCtGenre(CtGenre $ctGenre): self
    {
        if (!$this->ctGenres->contains($ctGenre)) {
            $this->ctGenres[] = $ctGenre;
            $ctGenre->setCtGenreCategorieId($this);
        }

        return $this;
    }

    public function removeCtGenre(CtGenre $ctGenre): self
    {
        if ($this->ctGenres->removeElement($ctGenre)) {
            // set the owning side to null (unless already changed)
            if ($ctGenre->getCtGenreCategorieId() === $this) {
                $ctGenre->setCtGenreCategorieId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtDroitPTAC>
     */
    public function getCtDroitPTACs(): Collection
    {
        return $this->ctDroitPTACs;
    }

    public function addCtDroitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if (!$this->ctDroitPTACs->contains($ctDroitPTAC)) {
            $this->ctDroitPTACs[] = $ctDroitPTAC;
            $ctDroitPTAC->setCtGenreCategorieId($this);
        }

        return $this;
    }

    public function removeCtDroitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if ($this->ctDroitPTACs->removeElement($ctDroitPTAC)) {
            // set the owning side to null (unless already changed)
            if ($ctDroitPTAC->getCtGenreCategorieId() === $this) {
                $ctDroitPTAC->setCtGenreCategorieId(null);
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
        return $this->getGcLibelle();
    }
}
