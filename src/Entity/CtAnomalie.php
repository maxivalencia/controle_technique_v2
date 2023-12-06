<?php

namespace App\Entity;

use App\Repository\CtAnomalieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtAnomalieRepository::class)
 */
class CtAnomalie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtAnomalieType::class, inversedBy="ctAnomalies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ct_anomalie_type_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anml_libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anml_code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anml_niveau_danger;

    /**
     * @ORM\ManyToMany(targetEntity=CtVisite::class, mappedBy="vst_anomalie_id")
     */
    private $ctVisites;

    public function __construct()
    {
        $this->ctVisites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtAnomalieTypeId(): ?CtAnomalieType
    {
        return $this->ct_anomalie_type_id;
    }

    public function setCtAnomalieTypeId(?CtAnomalieType $ct_anomalie_type_id): self
    {
        $this->ct_anomalie_type_id = $ct_anomalie_type_id;

        return $this;
    }

    public function getAnmlLibelle(): ?string
    {
        return strtoupper($this->anml_libelle);
    }

    public function setAnmlLibelle(string $anml_libelle): self
    {
        $this->anml_libelle = strtoupper($anml_libelle);

        return $this;
    }

    public function getAnmlCode(): ?string
    {
        return strtoupper($this->anml_code);
    }

    public function setAnmlCode(string $anml_code): self
    {
        $this->anml_code = strtoupper($anml_code);

        return $this;
    }

    public function getAnmlNiveauDanger(): ?int
    {
        return $this->anml_niveau_danger;
    }

    public function setAnmlNiveauDanger(?int $anml_niveau_danger): self
    {
        $this->anml_niveau_danger = $anml_niveau_danger;

        return $this;
    }

    /**
     * @return Collection<int, CtVisite>
     */
    public function getCtVisites(): Collection
    {
        return $this->ctVisites;
    }

    public function addCtVisite(CtVisite $ctVisite): self
    {
        if (!$this->ctVisites->contains($ctVisite)) {
            $this->ctVisites[] = $ctVisite;
            $ctVisite->addVstAnomalieId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            $ctVisite->removeVstAnomalieId($this);
        }

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getAnmlLibelle();
    }
}
