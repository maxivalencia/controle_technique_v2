<?php

namespace App\Entity;

use App\Repository\CtUsageTarifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtUsageTarifRepository::class)
 */
class CtUsageTarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUsage::class, inversedBy="ctUsageTarifs")
     */
    private $ct_usage_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usg_trf_annee;

    /**
     * @ORM\Column(type="float")
     */
    private $usg_trf_prix;

    /**
     * @ORM\ManyToOne(targetEntity=CtTypeVisite::class, inversedBy="ctUsageTarifs")
     */
    private $ct_type_visite_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtArretePrix::class, inversedBy="ctUsageTarifs")
     */
    private $ct_arrete_prix_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtUsageId(): ?CtUsage
    {
        return $this->ct_usage_id;
    }

    public function setCtUsageId(?CtUsage $ct_usage_id): self
    {
        $this->ct_usage_id = $ct_usage_id;

        return $this;
    }

    public function getUsgTrfAnnee(): ?string
    {
        return $this->usg_trf_annee;
    }

    public function setUsgTrfAnnee(string $usg_trf_annee): self
    {
        $this->usg_trf_annee = $usg_trf_annee;

        return $this;
    }

    public function getUsgTrfPrix(): ?float
    {
        return $this->usg_trf_prix;
    }

    public function setUsgTrfPrix(float $usg_trf_prix): self
    {
        $this->usg_trf_prix = $usg_trf_prix;

        return $this;
    }

    public function getCtTypeVisiteId(): ?CtTypeVisite
    {
        return $this->ct_type_visite_id;
    }

    public function setCtTypeVisiteId(?CtTypeVisite $ct_type_visite_id): self
    {
        $this->ct_type_visite_id = $ct_type_visite_id;

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

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getUsgTrfPrix();
    }
}
