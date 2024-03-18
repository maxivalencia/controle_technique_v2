<?php

namespace App\Entity;

use App\Repository\CtUtilisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtUtilisationRepository::class)
 */
class CtUtilisation
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
    private $ut_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_utilisation_id")
     */
    private $ctReceptions;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_utilisation_id")
     */
    private $ctVisites;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_utilisation_id")
     */
    private $ctConstAvDeds;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_utilisation_id")
     */
    private $ctAutreVentes;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_utilisation_id")
     */
    private $ctImprimeTechUses;

    public function __construct()
    {
        $this->ctReceptions = new ArrayCollection();
        $this->ctVisites = new ArrayCollection();
        $this->ctConstAvDeds = new ArrayCollection();
        $this->ctAutreVentes = new ArrayCollection();
        $this->ctImprimeTechUses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtLibelle(): ?string
    {
        return strtoupper($this->ut_libelle);
    }

    public function setUtLibelle(string $ut_libelle): self
    {
        $this->ut_libelle = strtoupper($ut_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtReception>
     */
    public function getCtReceptions(): Collection
    {
        return $this->ctReceptions;
    }

    public function addCtReception(CtReception $ctReception): self
    {
        if (!$this->ctReceptions->contains($ctReception)) {
            $this->ctReceptions[] = $ctReception;
            $ctReception->setCtUtilisationId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtUtilisationId() === $this) {
                $ctReception->setCtUtilisationId(null);
            }
        }

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
            $ctVisite->setCtUtilisationId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            // set the owning side to null (unless already changed)
            if ($ctVisite->getCtUtilisationId() === $this) {
                $ctVisite->setCtUtilisationId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDed>
     */
    public function getCtConstAvDeds(): Collection
    {
        return $this->ctConstAvDeds;
    }

    public function addCtConstAvDed(CtConstAvDed $ctVisite): self
    {
        if (!$this->ctConstAvDeds->contains($ctVisite)) {
            $this->ctConstAvDeds[] = $ctVisite;
            $ctVisite->setCtUtilisationId($this);
        }

        return $this;
    }

    public function removeCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if ($this->ctConstAvDeds->removeElement($ctConstAvDed)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDed->getCtUtilisationId() === $this) {
                $ctConstAvDed->setCtUtilisationId(null);
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
            $ctAutreVente->setCtUtilisationId($this);
        }

        return $this;
    }

    public function removeCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreVente->getCtUtilisationId() === $this) {
                $ctAutreVente->setCtUtilisationId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collections<int, CtImprimeTechUse>
     */
    public function getCtImprimeTechUses(): Collection
    {
        return $this->ctImprimeTechUses;
    }

    public function addCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if (!$this->ctImprimeTechUses->contains($ctImprimeTechUse)) {
            $this->ctImprimeTechUses[] = $ctImprimeTechUse;
            $ctImprimeTechUse->setCtUtilisationId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if ($this->ctImprimeTechUses->removeElement($ctImprimeTechUse)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTechUse->getCtUtilisationId() === $this) {
                $ctImprimeTechUse->setCtUtilisationId(null);
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
        return $this->getUtLibelle();
    }
}
