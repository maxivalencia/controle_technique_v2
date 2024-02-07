<?php

namespace App\Entity;

use App\Repository\CtAutreVenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtAutreVenteRepository::class)
 */
class CtAutreVente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUsageImprimeTechnique::class, inversedBy="ctAutreVentes")
     */
    private $ct_usage_it;

    /**
     * @ORM\ManyToOne(targetEntity=CtAutreTarif::class, inversedBy="ctAutreVentes")
     */
    private $ct_autre_tarif_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $auv_is_visible;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctAutreVentes")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctAutreVentes")
     */
    private $verificateur_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCarteGrise::class, inversedBy="ctAutreVentes")
     */
    private $ct_carte_grise_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctAutreVentes")
     */
    private $ct_centre_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $auv_created_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $controle_id;

    /**
     * @ORM\ManyToMany(targetEntity=CtVisiteExtra::class, inversedBy="ctAutreVentes", cascade={"persist", "remove"})
     */
    private $auv_extra;

    public function __construct()
    {
        $this->auv_extra = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtUsageIt(): ?CtUsageImprimeTechnique
    {
        return $this->ct_usage_it;
    }

    public function setCtUsageIt(?CtUsageImprimeTechnique $ct_usage_it): self
    {
        $this->ct_usage_it = $ct_usage_it;

        return $this;
    }

    public function getCtAutreTarifId(): ?CtAutreTarif
    {
        return $this->ct_autre_tarif_id;
    }

    public function setCtAutreTarifId(?CtAutreTarif $ct_autre_tarif_id): self
    {
        $this->ct_autre_tarif_id = $ct_autre_tarif_id;

        return $this;
    }

    public function isAuvIsVisible(): ?bool
    {
        return $this->auv_is_visible;
    }

    public function setAuvIsVisible(bool $auv_is_visible): self
    {
        $this->auv_is_visible = $auv_is_visible;

        return $this;
    }

    public function getUserId(): ?CtUser
    {
        return $this->user_id;
    }

    public function setUserId(?CtUser $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getVerificateurId(): ?CtUser
    {
        return $this->verificateur_id;
    }

    public function setVerificateurId(?CtUser $verificateur_id): self
    {
        $this->verificateur_id = $verificateur_id;

        return $this;
    }

    public function getCtCarteGriseId(): ?CtCarteGrise
    {
        return $this->ct_carte_grise_id;
    }

    public function setCtCarteGriseId(?CtCarteGrise $ct_carte_grise_id): self
    {
        $this->ct_carte_grise_id = $ct_carte_grise_id;

        return $this;
    }

    public function getCtCentreId(): ?CtCentre
    {
        return $this->ct_centre_id;
    }

    public function setCtCentreId(?CtCentre $ct_centre_id): self
    {
        $this->ct_centre_id = $ct_centre_id;

        return $this;
    }

    public function getAuvCreatedAt(): ?\DateTime
    {
        return $this->auv_created_at;
    }

    public function setAuvCreatedAt(?\DateTime $auv_created_at): self
    {
        $this->auv_created_at = $auv_created_at;

        return $this;
    }

    public function getControleId(): ?int
    {
        return $this->controle_id;
    }

    public function setControleId(?int $controle_id): self
    {
        $this->controle_id = $controle_id;

        return $this;
    }

    /**
     * @return Collection<int, CtVisiteExtra>
     */
    public function getAuvExtra(): Collection
    {
        return $this->auv_extra;
    }

    public function addAuvExtra(CtVisiteExtra $auvExtra): self
    {
        if (!$this->auv_extra->contains($auvExtra)) {
            $this->auv_extra[] = $auvExtra;
        }

        return $this;
    }

    public function removeAuvExtra(CtVisiteExtra $auvExtra): self
    {
        $this->auv_extra->removeElement($auvExtra);

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getControleId();
    }
}
