<?php

namespace App\Entity;

use App\Repository\CtCentreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtCentreRepository::class)
 */
class CtCentre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtProvince::class, inversedBy="ctCentres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ct_province_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ctr_nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ctr_code;

    /**
     * @ORM\Column(type="date")
     */
    private $ctr_created_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ctr_updated_at;

    /**
     * @ORM\OneToMany(targetEntity=CtHistorique::class, mappedBy="ct_center_id")
     */
    private $ctHistoriques;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctCentres")
     */
    private $centre_mere;

    /**
     * @ORM\OneToMany(targetEntity=CtCentre::class, mappedBy="centre_mere")
     */
    private $ctCentres;

    /**
     * @ORM\OneToMany(targetEntity=CtBordereau::class, mappedBy="ct_centre_id")
     */
    private $ctBordereaus;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="ct_centre_id")
     */
    private $ctCarteGrises;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDed::class, mappedBy="ct_centre_id")
     */
    private $ctConstAvDeds;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTechUse::class, mappedBy="ct_centre_id")
     */
    private $ctImprimeTechUses;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_centre_id")
     */
    private $ctReceptions;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_centre_id")
     */
    private $ctVisites;

    /**
     * @ORM\OneToMany(targetEntity=CtAutreVente::class, mappedBy="ct_centre_id")
     */
    private $ctAutreVentes;

    /**
     * @ORM\OneToMany(targetEntity=CtUser::class, mappedBy="ct_centre_id")
     */
    private $ctUsers;

    public function __construct()
    {
        $this->ctHistoriques = new ArrayCollection();
        $this->ctCentres = new ArrayCollection();
        $this->ctBordereaus = new ArrayCollection();
        $this->ctCarteGrises = new ArrayCollection();
        $this->ctConstAvDeds = new ArrayCollection();
        $this->ctImprimeTechUses = new ArrayCollection();
        $this->ctReceptions = new ArrayCollection();
        $this->ctVisites = new ArrayCollection();
        $this->ctAutreVentes = new ArrayCollection();
        $this->ctUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtProvinceId(): ?CtProvince
    {
        return $this->ct_province_id;
    }

    public function setCtProvinceId(?CtProvince $ct_province_id): self
    {
        $this->ct_province_id = $ct_province_id;

        return $this;
    }

    public function getCtrNom(): ?string
    {
        return strtoupper($this->ctr_nom);
    }

    public function setCtrNom(string $ctr_nom): self
    {
        $this->ctr_nom = strtoupper($ctr_nom);

        return $this;
    }

    public function getCtrCode(): ?string
    {
        return strtoupper($this->ctr_code);
    }

    public function setCtrCode(string $ctr_code): self
    {
        $this->ctr_code = strtoupper($ctr_code);

        return $this;
    }

    public function getCtrCreatedAt(): ?\DateTimeInterface
    {
        return $this->ctr_created_at;
    }

    public function setCtrCreatedAt(\DateTimeInterface $ctr_created_at): self
    {
        $this->ctr_created_at = $ctr_created_at;

        return $this;
    }

    public function getCtrUpdatedAt(): ?\DateTimeInterface
    {
        return $this->ctr_updated_at;
    }

    public function setCtrUpdatedAt(?\DateTimeInterface $ctr_updated_at): self
    {
        $this->ctr_updated_at = $ctr_updated_at;

        return $this;
    }

    /**
     * @return Collection<int, CtHistorique>
     */
    public function getCtHistoriques(): Collection
    {
        return $this->ctHistoriques;
    }

    public function addCtHistorique(CtHistorique $ctHistorique): self
    {
        if (!$this->ctHistoriques->contains($ctHistorique)) {
            $this->ctHistoriques[] = $ctHistorique;
            $ctHistorique->setCtCenterId($this);
        }

        return $this;
    }

    public function removeCtHistorique(CtHistorique $ctHistorique): self
    {
        if ($this->ctHistoriques->removeElement($ctHistorique)) {
            // set the owning side to null (unless already changed)
            if ($ctHistorique->getCtCenterId() === $this) {
                $ctHistorique->setCtCenterId(null);
            }
        }

        return $this;
    }

    public function getCentreMere(): ?self
    {
        return $this->centre_mere;
    }

    public function setCentreMere(?self $centre_mere): self
    {
        $this->centre_mere = $centre_mere;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCtCentres(): Collection
    {
        return $this->ctCentres;
    }

    public function addCtCentre(self $ctCentre): self
    {
        if (!$this->ctCentres->contains($ctCentre)) {
            $this->ctCentres[] = $ctCentre;
            $ctCentre->setCentreMere($this);
        }

        return $this;
    }

    public function removeCtCentre(self $ctCentre): self
    {
        if ($this->ctCentres->removeElement($ctCentre)) {
            // set the owning side to null (unless already changed)
            if ($ctCentre->getCentreMere() === $this) {
                $ctCentre->setCentreMere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtBordereau>
     */
    public function getCtBordereaus(): Collection
    {
        return $this->ctBordereaus;
    }

    public function addCtBordereau(CtBordereau $ctBordereau): self
    {
        if (!$this->ctBordereaus->contains($ctBordereau)) {
            $this->ctBordereaus[] = $ctBordereau;
            $ctBordereau->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtBordereau(CtBordereau $ctBordereau): self
    {
        if ($this->ctBordereaus->removeElement($ctBordereau)) {
            // set the owning side to null (unless already changed)
            if ($ctBordereau->getCtCentreId() === $this) {
                $ctBordereau->setCtCentreId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtCarteGrise>
     */
    public function getCtCarteGrises(): Collection
    {
        return $this->ctCarteGrises;
    }

    public function addCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if (!$this->ctCarteGrises->contains($ctCarteGrise)) {
            $this->ctCarteGrises[] = $ctCarteGrise;
            $ctCarteGrise->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCtCentreId() === $this) {
                $ctCarteGrise->setCtCentreId(null);
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

    public function addCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if (!$this->ctConstAvDeds->contains($ctConstAvDed)) {
            $this->ctConstAvDeds[] = $ctConstAvDed;
            $ctConstAvDed->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if ($this->ctConstAvDeds->removeElement($ctConstAvDed)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDed->getCtCentreId() === $this) {
                $ctConstAvDed->setCtCentreId(null);
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
            $ctImprimeTechUse->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if ($this->ctImprimeTechUses->removeElement($ctImprimeTechUse)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTechUse->getCtCentreId() === $this) {
                $ctImprimeTechUse->setCtCentreId(null);
            }
        }

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
            $ctReception->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtCentreId() === $this) {
                $ctReception->setCtCentreId(null);
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
            $ctVisite->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            // set the owning side to null (unless already changed)
            if ($ctVisite->getCtCentreId() === $this) {
                $ctVisite->setCtCentreId(null);
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
            $ctAutreVente->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreVente->getCtCentreId() === $this) {
                $ctAutreVente->setCtCentreId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtUser>
     */
    public function getCtUsers(): Collection
    {
        return $this->ctUsers;
    }

    public function addCtUser(CtUser $ctUser): self
    {
        if (!$this->ctUsers->contains($ctUser)) {
            $this->ctUsers[] = $ctUser;
            $ctUser->setCtCentreId($this);
        }

        return $this;
    }

    public function removeCtUser(CtUser $ctUser): self
    {
        if ($this->ctUsers->removeElement($ctUser)) {
            // set the owning side to null (unless already changed)
            if ($ctUser->getCtCentreId() === $this) {
                $ctUser->setCtCentreId(null);
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
        return $this->getCtrNom();
    }
}
