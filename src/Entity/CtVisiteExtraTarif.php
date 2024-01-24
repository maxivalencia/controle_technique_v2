<?php

namespace App\Entity;

use App\Repository\CtVisiteExtraTarifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtVisiteExtraTarifRepository::class)
 */
class CtVisiteExtraTarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtImprimeTech::class, inversedBy="ctVisiteExtraTarifs")
     */
    private $ct_imprime_tech_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vet_annee;

    /**
     * @ORM\Column(type="float")
     */
    private $vet_prix;

    /**
     * @ORM\ManyToOne(targetEntity=CtArretePrix::class, inversedBy="ctVisiteExtraTarifs")
     */
    private $ct_arrete_prix_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtImprimeTechId(): ?CtImprimeTech
    {
        return $this->ct_imprime_tech_id;
    }

    public function setCtImprimeTechId(?CtImprimeTech $ct_imprime_tech_id): self
    {
        $this->ct_imprime_tech_id = $ct_imprime_tech_id;

        return $this;
    }

    public function getVetAnnee(): ?string
    {
        return $this->vet_annee;
    }

    public function setVetAnnee(string $vet_annee): self
    {
        $this->vet_annee = $vet_annee;

        return $this;
    }

    public function getVetPrix(): ?float
    {
        return $this->vet_prix;
    }

    public function setVetPrix(float $vet_prix): self
    {
        $this->vet_prix = $vet_prix;

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
        return $this->getVetPrix();
    }
}
