<?php

namespace App\Entity;

use App\Repository\CtVisiteExtraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtVisiteExtraRepository::class)
 */
class CtVisiteExtra
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
    private $vste_libelle;

    /**
     * @ORM\ManyToMany(targetEntity=CtVisite::class, mappedBy="vst_extra")
     */
    private $ctVisites;

    /**
     * @ORM\OneToMany(targetEntity=CtExtraVente::class, mappedBy="ct_visite_extra_id")
     */
    private $ctExtraVentes;

    /**
     * @ORM\ManyToMany(targetEntity=CtAutreVente::class, mappedBy="auv_extra")
     */
    private $ctAutreVentes;

    public function __construct()
    {
        $this->ctVisites = new ArrayCollection();
        $this->ctExtraVentes = new ArrayCollection();
        $this->ctAutreVentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVsteLibelle(): ?string
    {
        return strtoupper($this->vste_libelle);
    }

    public function setVsteLibelle(string $vste_libelle): self
    {
        $this->vste_libelle = strtoupper($vste_libelle);

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
            $ctVisite->addVstExtra($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            $ctVisite->removeVstExtra($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CtExtraVente>
     */
    public function getCtExtraVentes(): Collection
    {
        return $this->ctExtraVentes;
    }

    public function addCtExtraVente(CtExtraVente $ctExtraVente): self
    {
        if (!$this->ctExtraVentes->contains($ctExtraVente)) {
            $this->ctExtraVentes[] = $ctExtraVente;
            $ctExtraVente->setCtVisiteExtraId($this);
        }

        return $this;
    }

    public function removeCtExtraVente(CtExtraVente $ctExtraVente): self
    {
        if ($this->ctExtraVentes->removeElement($ctExtraVente)) {
            // set the owning side to null (unless already changed)
            if ($ctExtraVente->getCtVisiteExtraId() === $this) {
                $ctExtraVente->setCtVisiteExtraId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtVisite>
     */
    public function getCtAutreVentes(): Collection
    {
        return $this->ctAutreVentes;
    }

    public function addCtAutreVentes(CtVisite $ctAutreVente): self
    {
        if (!$this->ctAutreVentes->contains($ctAutreVente)) {
            $this->ctAutreVentes[] = $ctAutreVente;
            $ctAutreVente->addVstExtra($this);
        }

        return $this;
    }

    public function removeCtAutreVentes(CtVisite $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            $ctAutreVente->removeVstExtra($this);
        }

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getVsteLibelle();
    }
}
