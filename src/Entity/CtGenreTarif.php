<?php

namespace App\Entity;

use App\Repository\CtGenreTarifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtGenreTarifRepository::class)
 */
class CtGenreTarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtGenre::class, inversedBy="ctGenreTarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ct_genre_id;

    /**
     * @ORM\Column(type="float")
     */
    private $grt_prix;

    /**
     * @ORM\Column(type="date")
     */
    private $grt_annee;

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

    public function getGrtPrix(): ?float
    {
        return $this->grt_prix;
    }

    public function setGrtPrix(float $grt_prix): self
    {
        $this->grt_prix = $grt_prix;

        return $this;
    }

    public function getGrtAnnee(): ?\DateTimeInterface
    {
        return $this->grt_annee;
    }

    public function setGrtAnnee(\DateTimeInterface $grt_annee): self
    {
        $this->grt_annee = $grt_annee;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getGrtPrix();
    }
}
