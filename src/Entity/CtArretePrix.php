<?php

namespace App\Entity;

use App\Repository\CtArretePrixRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtArretePrixRepository::class)
 */
class CtArretePrix
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctArretePrixes")
     */
    private $ct_user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $art_numero;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $art_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $art_date_application;

    /**
     * @ORM\Column(type="date")
     */
    private $art_created_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $art_updated_at;

    /**
     * @ORM\OneToMany(targetEntity=CtDroitPTAC::class, mappedBy="ct_arrete_prix_id")
     */
    private $ctDroitPTACs;

    /**
     * @ORM\OneToMany(targetEntity=CtProcesVerbal::class, mappedBy="ct_arrete_prix_id")
     */
    private $ctProcesVerbals;

    /**
     * @ORM\OneToMany(targetEntity=CtMotifTarif::class, mappedBy="ct_arrete_prix")
     */
    private $ctMotifTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtUsageTarif::class, mappedBy="ct_arrete_prix_id")
     */
    private $ctUsageTarifs;

    /**
     * @ORM\OneToMany(targetEntity=CtVisiteExtraTarif::class, mappedBy="ct_arrete_prix_id")
     */
    private $ctVisiteExtraTarifs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $art_observation;

    public function __construct()
    {
        $this->ctDroitPTACs = new ArrayCollection();
        $this->ctProcesVerbals = new ArrayCollection();
        $this->ctMotifTarifs = new ArrayCollection();
        $this->ctUsageTarifs = new ArrayCollection();
        $this->ctVisiteExtraTarifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtUserId(): ?CtUser
    {
        return $this->ct_user_id;
    }

    public function setCtUserId(?CtUser $ct_user_id): self
    {
        $this->ct_user_id = $ct_user_id;

        return $this;
    }

    public function getArtNumero(): ?string
    {
        return strtoupper($this->art_numero);
    }

    public function setArtNumero(string $art_numero): self
    {
        $this->art_numero = strtoupper($art_numero);

        return $this;
    }

    public function getArtDate(): ?\DateTimeInterface
    {
        return $this->art_date;
    }

    public function setArtDate(?\DateTimeInterface $art_date): self
    {
        $this->art_date = $art_date;

        return $this;
    }

    public function getArtDateApplication(): ?\DateTimeInterface
    {
        return $this->art_date_application;
    }

    public function setArtDateApplication(?\DateTimeInterface $art_date_application): self
    {
        $this->art_date_application = $art_date_application;

        return $this;
    }

    public function getArtCreatedAt(): ?\DateTimeInterface
    {
        return $this->art_created_at;
    }

    public function setArtCreatedAt(\DateTimeInterface $art_created_at): self
    {
        $this->art_created_at = $art_created_at;

        return $this;
    }

    public function getArtUpdatedAt(): ?\DateTimeInterface
    {
        return $this->art_updated_at;
    }

    public function setArtUpdatedAt(?\DateTimeInterface $art_updated_at): self
    {
        $this->art_updated_at = $art_updated_at;

        return $this;
    }

    /**
     * @return Collection<int, CtDroitPTAC>
     */
    public function getCtDroitPTACs(): Collection
    {
        return $this->ctDroitPTACs;
    }

    public function addCtDroitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if (!$this->ctDroitPTACs->contains($ctDroitPTAC)) {
            $this->ctDroitPTACs[] = $ctDroitPTAC;
            $ctDroitPTAC->setCtArretePrixId($this);
        }

        return $this;
    }

    public function removeCtDroitPTAC(CtDroitPTAC $ctDroitPTAC): self
    {
        if ($this->ctDroitPTACs->removeElement($ctDroitPTAC)) {
            // set the owning side to null (unless already changed)
            if ($ctDroitPTAC->getCtArretePrixId() === $this) {
                $ctDroitPTAC->setCtArretePrixId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtProcesVerbal>
     */
    public function getCtProcesVerbals(): Collection
    {
        return $this->ctProcesVerbals;
    }

    public function addCtProcesVerbal(CtProcesVerbal $ctProcesVerbal): self
    {
        if (!$this->ctProcesVerbals->contains($ctProcesVerbal)) {
            $this->ctProcesVerbals[] = $ctProcesVerbal;
            $ctProcesVerbal->setCtArretePrixId($this);
        }

        return $this;
    }

    public function removeCtProcesVerbal(CtProcesVerbal $ctProcesVerbal): self
    {
        if ($this->ctProcesVerbals->removeElement($ctProcesVerbal)) {
            // set the owning side to null (unless already changed)
            if ($ctProcesVerbal->getCtArretePrixId() === $this) {
                $ctProcesVerbal->setCtArretePrixId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtMotifTarif>
     */
    public function getCtMotifTarifs(): Collection
    {
        return $this->ctMotifTarifs;
    }

    public function addCtMotifTarif(CtMotifTarif $ctMotifTarif): self
    {
        if (!$this->ctMotifTarifs->contains($ctMotifTarif)) {
            $this->ctMotifTarifs[] = $ctMotifTarif;
            $ctMotifTarif->setCtArretePrix($this);
        }

        return $this;
    }

    public function removeCtMotifTarif(CtMotifTarif $ctMotifTarif): self
    {
        if ($this->ctMotifTarifs->removeElement($ctMotifTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctMotifTarif->getCtArretePrix() === $this) {
                $ctMotifTarif->setCtArretePrix(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtUsageTarif>
     */
    public function getCtUsageTarifs(): Collection
    {
        return $this->ctUsageTarifs;
    }

    public function addCtUsageTarif(CtUsageTarif $ctUsageTarif): self
    {
        if (!$this->ctUsageTarifs->contains($ctUsageTarif)) {
            $this->ctUsageTarifs[] = $ctUsageTarif;
            $ctUsageTarif->setCtArretePrixId($this);
        }

        return $this;
    }

    public function removeCtUsageTarif(CtUsageTarif $ctUsageTarif): self
    {
        if ($this->ctUsageTarifs->removeElement($ctUsageTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctUsageTarif->getCtArretePrixId() === $this) {
                $ctUsageTarif->setCtArretePrixId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtVisiteExtraTarif>
     */
    public function getCtVisiteExtraTarifs(): Collection
    {
        return $this->ctVisiteExtraTarifs;
    }

    public function addCtVisiteExtraTarif(CtVisiteExtraTarif $ctVisiteExtraTarif): self
    {
        if (!$this->ctVisiteExtraTarifs->contains($ctVisiteExtraTarif)) {
            $this->ctVisiteExtraTarifs[] = $ctVisiteExtraTarif;
            $ctVisiteExtraTarif->setCtArretePrixId($this);
        }

        return $this;
    }

    public function removeCtVisiteExtraTarif(CtVisiteExtraTarif $ctVisiteExtraTarif): self
    {
        if ($this->ctVisiteExtraTarifs->removeElement($ctVisiteExtraTarif)) {
            // set the owning side to null (unless already changed)
            if ($ctVisiteExtraTarif->getCtArretePrixId() === $this) {
                $ctVisiteExtraTarif->setCtArretePrixId(null);
            }
        }

        return $this;
    }

    public function getArtObservation(): ?string
    {
        return $this->art_observation;
    }

    public function setArtObservation(?string $art_observation): self
    {
        $this->art_observation = $art_observation;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getArtNumero();
    }
}
