<?php

namespace App\Entity;

use App\Repository\CtUsageImprimeTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtUsageImprimeTechniqueRepository::class)
 */
class CtUsageImprimeTechnique
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
    private $uit_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtAutreTarif::class, mappedBy="ct_usage_imprime_technique_id")
     */
    private $ctAutreTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTechUse::class, mappedBy="ct_usage_it_id")
     */
    private $ctImprimeTechUses;

    /**
     * @ORM\OneToMany(targetEntity=CtPhoto::class, mappedBy="ct_usage_it")
     */
    private $ctPhotos;

    /**
     * @ORM\OneToMany(targetEntity=CtAutreVente::class, mappedBy="ct_usage_it")
     */
    private $ctAutreVentes;

    public function __construct()
    {
        $this->ctAutreTarifs = new ArrayCollection();
        $this->ctImprimeTechUses = new ArrayCollection();
        $this->ctPhotos = new ArrayCollection();
        $this->ctAutreVentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUitLibelle(): ?string
    {
        return strtoupper($this->uit_libelle);
    }

    public function setUitLibelle(string $uit_libelle): self
    {
        $this->uit_libelle = strtoupper($uit_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtAutreTarif>
     */
    public function getCtAutreTarifs(): Collection
    {
        return $this->ctAutreTarifs;
    }

    public function addCtAutreTarif(CtAutreTarif $ctAutreTarif): self
    {
        if (!$this->ctAutreTarifs->contains($ctAutreTarif)) {
            $this->ctAutreTarifs[] = $ctAutreTarif;
            $ctAutreTarif->setCtUsageImprimeTechniqueId($this);
        }

        return $this;
    }

    public function removeCtAutreTarif(CtAutreTarif $ctAutreTarif): self
    {
        if ($this->ctAutreTarifs->removeElement($ctAutreTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreTarif->getCtUsageImprimeTechniqueId() === $this) {
                $ctAutreTarif->setCtUsageImprimeTechniqueId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtImprimeTechUse>
     */
    public function getCtImprimeTechUses(): Collection
    {
        return $this->ctImprimeTechUses;
    }

    public function addCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if (!$this->ctImprimeTechUses->contains($ctImprimeTechUse)) {
            $this->ctImprimeTechUses[] = $ctImprimeTechUse;
            $ctImprimeTechUse->setCtUsageItId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if ($this->ctImprimeTechUses->removeElement($ctImprimeTechUse)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTechUse->getCtUsageItId() === $this) {
                $ctImprimeTechUse->setCtUsageItId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtPhoto>
     */
    public function getCtPhotos(): Collection
    {
        return $this->ctPhotos;
    }

    public function addCtPhoto(CtPhoto $ctPhoto): self
    {
        if (!$this->ctPhotos->contains($ctPhoto)) {
            $this->ctPhotos[] = $ctPhoto;
            $ctPhoto->setCtUsageIt($this);
        }

        return $this;
    }

    public function removeCtPhoto(CtPhoto $ctPhoto): self
    {
        if ($this->ctPhotos->removeElement($ctPhoto)) {
            // set the owning side to null (unless already changed)
            if ($ctPhoto->getCtUsageIt() === $this) {
                $ctPhoto->setCtUsageIt(null);
            }
        }

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
            $ctAutreVente->setCtUsageIt($this);
        }

        return $this;
    }

    public function removeCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreVente->getCtUsageIt() === $this) {
                $ctAutreVente->setCtUsageIt(null);
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
        return $this->getUitLibelle();
    }
}
