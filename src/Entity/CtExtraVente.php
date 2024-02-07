<?php

namespace App\Entity;

use App\Repository\CtExtraVenteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtExtraVenteRepository::class)
 */
class CtExtraVente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtVisite::class, inversedBy="ctExtraVentes")
     */
    private $ct_visite_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtVisiteExtra::class, inversedBy="ctExtraVentes")
     */
    private $ct_visite_extra_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $exv_created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exv_is_active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtVisiteId(): ?CtVisite
    {
        return $this->ct_visite_id;
    }

    public function setCtVisiteId(?CtVisite $ct_visite_id): self
    {
        $this->ct_visite_id = $ct_visite_id;

        return $this;
    }

    public function getCtVisiteExtraId(): ?CtVisiteExtra
    {
        return $this->ct_visite_extra_id;
    }

    public function setCtVisiteExtraId(?CtVisiteExtra $ct_visite_extra_id): self
    {
        $this->ct_visite_extra_id = $ct_visite_extra_id;

        return $this;
    }

    public function getExvCreatedAt(): ?\DateTimeImmutable
    {
        return $this->exv_created_at;
    }

    public function setExvCreatedAt(?\DateTimeImmutable $exv_created_at): self
    {
        $this->exv_created_at = $exv_created_at;

        return $this;
    }

    public function isExvIsActive(): ?bool
    {
        return $this->exv_is_active;
    }

    public function setExvIsActive(bool $exv_is_active): self
    {
        $this->exv_is_active = $exv_is_active;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getCtVisiteId().' '. $this->getExvCreatedAt();
    }
}
