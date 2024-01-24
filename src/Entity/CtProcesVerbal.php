<?php

namespace App\Entity;

use App\Repository\CtProcesVerbalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtProcesVerbalRepository::class)
 */
class CtProcesVerbal
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
    private $pv_type;

    /**
     * @ORM\Column(type="float")
     */
    private $pv_tarif;

    /**
     * @ORM\ManyToOne(targetEntity=CtArretePrix::class, inversedBy="ctProcesVerbals")
     */
    private $ct_arrete_prix_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPvType(): ?string
    {
        return strtoupper($this->pv_type);
    }

    public function setPvType(string $pv_type): self
    {
        $this->pv_type = strtoupper($pv_type);

        return $this;
    }

    public function getPvTarif(): ?float
    {
        return $this->pv_tarif;
    }

    public function setPvTarif(float $pv_tarif): self
    {
        $this->pv_tarif = $pv_tarif;

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
        return $this->getPvType();
    }
}
