<?php

namespace App\Entity;

use App\Repository\CtMotifTarifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtMotifTarifRepository::class)
 */
class CtMotifTarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtMotif::class, inversedBy="ctMotifTarifs")
     */
    private $ct_motif_id;

    /**
     * @ORM\Column(type="float")
     */
    private $mtf_trf_prix;

    /**
     * @ORM\Column(type="date")
     */
    private $mtf_trf_date;

    /**
     * @ORM\ManyToOne(targetEntity=CtArretePrix::class, inversedBy="ctMotifTarifs")
     */
    private $ct_arrete_prix;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMtfTrfPrix(): ?float
    {
        return $this->mtf_trf_prix;
    }

    public function setMtfTrfPrix(float $mtf_trf_prix): self
    {
        $this->mtf_trf_prix = $mtf_trf_prix;

        return $this;
    }

    public function getMtfTrfDate(): ?\DateTimeInterface
    {
        return $this->mtf_trf_date;
    }

    public function setMtfTrfDate(\DateTimeInterface $mtf_trf_date): self
    {
        $this->mtf_trf_date = $mtf_trf_date;

        return $this;
    }

    public function getCtArretePrix(): ?CtArretePrix
    {
        return $this->ct_arrete_prix;
    }

    public function setCtArretePrix(?CtArretePrix $ct_arrete_prix): self
    {
        $this->ct_arrete_prix = $ct_arrete_prix;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getMtfTrfPrix();
    }
}
