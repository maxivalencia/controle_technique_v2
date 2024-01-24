<?php

namespace App\Entity;

use App\Repository\CtAutreTarifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtAutreTarifRepository::class)
 */
class CtAutreTarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUsageImprimeTechnique::class, inversedBy="ctAutreTarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ct_usage_imprime_technique_id;

    /**
     * @ORM\Column(type="float")
     */
    private $aut_prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $aut_arrete;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $aut_date;

    /**
     * @ORM\OneToMany(targetEntity=CtAutreVente::class, mappedBy="ct_autre_tarif_id")
     */
    private $ctAutreVentes;

    public function __construct()
    {
        $this->ctAutreVentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtUsageImprimeTechniqueId(): ?CtUsageImprimeTechnique
    {
        return $this->ct_usage_imprime_technique_id;
    }

    public function setCtUsageImprimeTechniqueId(?CtUsageImprimeTechnique $ct_usage_imprime_technique_id): self
    {
        $this->ct_usage_imprime_technique_id = $ct_usage_imprime_technique_id;

        return $this;
    }

    public function getAutPrix(): ?float
    {
        return $this->aut_prix;
    }

    public function setAutPrix(float $aut_prix): self
    {
        $this->aut_prix = $aut_prix;

        return $this;
    }

    public function getAutArrete(): ?string
    {
        return strtoupper($this->aut_arrete);
    }

    public function setAutArrete(string $aut_arrete): self
    {
        $this->aut_arrete = strtoupper($aut_arrete);

        return $this;
    }

    public function getAutDate(): ?\DateTimeInterface
    {
        return $this->aut_date;
    }

    public function setAutDate(?\DateTimeInterface $aut_date): self
    {
        $this->aut_date = $aut_date;

        return $this;
    }

    /**
     * @return Collection<int, CtAutreVente>
     */
    public function getCtAutreVentes(): Collection
    {
        return $this->ctAutreVentes;
    }

    public function addCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if (!$this->ctAutreVentes->contains($ctAutreVente)) {
            $this->ctAutreVentes[] = $ctAutreVente;
            $ctAutreVente->setCtAutreTarifId($this);
        }

        return $this;
    }

    public function removeCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreVente->getCtAutreTarifId() === $this) {
                $ctAutreVente->setCtAutreTarifId(null);
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
        return $this->getAutArrete();
    }
}
