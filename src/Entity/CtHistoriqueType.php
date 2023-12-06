<?php

namespace App\Entity;

use App\Repository\CtHistoriqueTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtHistoriqueTypeRepository::class)
 */
class CtHistoriqueType
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
    private $hst_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtHistorique::class, mappedBy="ct_historique_type_id")
     */
    private $ctHistoriques;

    public function __construct()
    {
        $this->ctHistoriques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHstLibelle(): ?string
    {
        return strtoupper($this->hst_libelle);
    }

    public function setHstLibelle(string $hst_libelle): self
    {
        $this->hst_libelle = strtoupper($hst_libelle);

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
            $ctHistorique->setCtHistoriqueTypeId($this);
        }

        return $this;
    }

    public function removeCtHistorique(CtHistorique $ctHistorique): self
    {
        if ($this->ctHistoriques->removeElement($ctHistorique)) {
            // set the owning side to null (unless already changed)
            if ($ctHistorique->getCtHistoriqueTypeId() === $this) {
                $ctHistorique->setCtHistoriqueTypeId(null);
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
        return $this->getHstLibelle();
    }
}
