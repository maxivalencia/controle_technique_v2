<?php

namespace App\Entity;

use App\Repository\CtTypeImprimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(repositoryClass=CtTypeImprimeRepository::class)
*/
class CtTypeImprime
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
    private $tit_libelle;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTech::class, mappedBy="ct_type_imprime_id")
     */
    private $ct_imprim_tech_id;

    public function __construct()
    {
        $this->ct_imprim_tech_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitLibelle(): ?string
    {
        return strtoupper($this->tit_libelle);
    }

    public function setTitLibelle(string $tit_libelle): self
    {
        $this->tit_libelle = strtoupper($tit_libelle);

        return $this;
    }

    /**
     * @return Collection<int, CtImprimeTechUse>
     */
    public function getCtImprimeTechId(): Collection
    {
        return $this->ct_imprim_tech_id;
    }

    public function addCtImprimeTechId(CtImprimeTech $ct_imprim_tech_id): self
    {
        if (!$this->ct_imprim_tech_id->contains($ct_imprim_tech_id)) {
            $this->ct_imprim_tech_id[] = $ct_imprim_tech_id;
            $ct_imprim_tech_id->setCtTypeImprimeId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechId(CtImprimeTech $ct_imprim_tech_id): self
    {
        if ($this->ct_imprim_tech_id->removeElement($ct_imprim_tech_id)) {
            // set the owning side to null (unless already changed)
            if ($ct_imprim_tech_id->getCtTypeImprimeId() === $this) {
                $ct_imprim_tech_id->setCtTypeImprimeId(null);
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
        return $this->getTitLibelle();
    }
}
