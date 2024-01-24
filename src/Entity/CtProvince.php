<?php

namespace App\Entity;

use App\Repository\CtProvinceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtProvinceRepository::class)
 */
class CtProvince
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
    private $prv_nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prv_code;

    /**
     * @ORM\Column(type="date")
     */
    private $prv_created_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $prv_updated_at;

    /**
     * @ORM\OneToMany(targetEntity=CtCentre::class, mappedBy="ct_province_id")
     */
    private $ctCentres;

    public function __construct()
    {
        $this->ctCentres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrvNom(): ?string
    {
        return strtoupper($this->prv_nom);
    }

    public function setPrvNom(string $prv_nom): self
    {
        $this->prv_nom = strtoupper($prv_nom);

        return $this;
    }

    public function getPrvCode(): ?string
    {
        return strtoupper($this->prv_code);
    }

    public function setPrvCode(string $prv_code): self
    {
        $this->prv_code = strtoupper($prv_code);

        return $this;
    }

    public function getPrvCreatedAt(): ?\DateTimeInterface
    {
        return $this->prv_created_at;
    }

    public function setPrvCreatedAt(\DateTimeInterface $prv_created_at): self
    {
        $this->prv_created_at = $prv_created_at;

        return $this;
    }

    public function getPrvUpdatedAt(): ?\DateTimeInterface
    {
        return $this->prv_updated_at;
    }

    public function setPrvUpdatedAt(?\DateTimeInterface $prv_updated_at): self
    {
        $this->prv_updated_at = $prv_updated_at;

        return $this;
    }

    /**
     * @return Collection<int, CtCentre>
     */
    public function getCtCentres(): Collection
    {
        return $this->ctCentres;
    }

    public function addCtCentre(CtCentre $ctCentre): self
    {
        if (!$this->ctCentres->contains($ctCentre)) {
            $this->ctCentres[] = $ctCentre;
            $ctCentre->setCtProvinceId($this);
        }

        return $this;
    }

    public function removeCtCentre(CtCentre $ctCentre): self
    {
        if ($this->ctCentres->removeElement($ctCentre)) {
            // set the owning side to null (unless already changed)
            if ($ctCentre->getCtProvinceId() === $this) {
                $ctCentre->setCtProvinceId(null);
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
        return $this->getPrvNom();
    }
}
