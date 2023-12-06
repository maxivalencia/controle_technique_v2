<?php

namespace App\Entity;

use App\Repository\CtPhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtPhotoRepository::class)
 */
class CtPhoto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ct_controle_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pht_nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pht_dossier;

    /**
     * @ORM\ManyToOne(targetEntity=CtUsageImprimeTechnique::class, inversedBy="ctPhotos")
     */
    private $ct_usage_it;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtControleId(): ?int
    {
        return $this->ct_controle_id;
    }

    public function setCtControleId(?int $ct_controle_id): self
    {
        $this->ct_controle_id = $ct_controle_id;

        return $this;
    }

    public function getPhtNom(): ?string
    {
        return strtoupper($this->pht_nom);
    }

    public function setPhtNom(?string $pht_nom): self
    {
        $this->pht_nom = strtoupper($pht_nom);

        return $this;
    }

    public function getPhtDossier(): ?string
    {
        return $this->pht_dossier;
    }

    public function setPhtDossier(?string $pht_dossier): self
    {
        $this->pht_dossier = $pht_dossier;

        return $this;
    }

    public function getCtUsageIt(): ?CtUsageImprimeTechnique
    {
        return $this->ct_usage_it;
    }

    public function setCtUsageIt(?CtUsageImprimeTechnique $ct_usage_it): self
    {
        $this->ct_usage_it = $ct_usage_it;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getPhtNom();
    }
}
