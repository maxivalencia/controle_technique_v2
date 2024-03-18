<?php

namespace App\Entity;

use App\Repository\CtImprimeTechUseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtImprimeTechUseRepository::class)
 */
class CtImprimeTechUse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtBordereau::class, inversedBy="ctImprimeTechUses")
     */
    private $ct_bordereau_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctImprimeTechUses")
     */
    private $ct_centre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtImprimeTech::class, inversedBy="ctImprimeTechUses")
     */
    private $ct_imprime_tech_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctImprimeTechUses")
     */
    private $ct_user_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ct_controle_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $itu_numero;

    /**
     * @ORM\Column(type="boolean")
     */
    private $itu_used;

    /**
     * @ORM\ManyToOne(targetEntity=CtUsageImprimeTechnique::class, inversedBy="ctImprimeTechUses")
     */
    private $ct_usage_it_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actived_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $itu_observation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $itu_is_visible;

    /**
     * @ORM\ManyToOne(targetEntity=CtUtilisation::class, inversedBy="ctImprimeTechUses")
     */
    private $ct_utilisation_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCtBordereauId(): ?CtBordereau
    {
        return $this->ct_bordereau_id;
    }

    public function setCtBordereauId(?CtBordereau $ct_bordereau_id): self
    {
        $this->ct_bordereau_id = $ct_bordereau_id;

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

    public function getCtImprimeTechId(): ?CtImprimeTech
    {
        return $this->ct_imprime_tech_id;
    }

    public function setCtImprimeTechId(?CtImprimeTech $ct_imprime_tech_id): self
    {
        $this->ct_imprime_tech_id = $ct_imprime_tech_id;

        return $this;
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

    public function getCtControleId(): ?int
    {
        return $this->ct_controle_id;
    }

    public function setCtControleId(?int $ct_controle_id): self
    {
        $this->ct_controle_id = $ct_controle_id;

        return $this;
    }

    public function getItuNumero(): ?int
    {
        return $this->itu_numero;
    }

    public function setItuNumero(int $itu_numero): self
    {
        $this->itu_numero = $itu_numero;

        return $this;
    }

    public function isItuUsed(): ?bool
    {
        return $this->itu_used;
    }

    public function setItuUsed(bool $itu_used): self
    {
        $this->itu_used = $itu_used;

        return $this;
    }

    public function getCtUsageItId(): ?CtUsageImprimeTechnique
    {
        return $this->ct_usage_it_id;
    }

    public function setCtUsageItId(?CtUsageImprimeTechnique $ct_usage_it_id): self
    {
        $this->ct_usage_it_id = $ct_usage_it_id;

        return $this;
    }

    public function getActivedAt(): ?\DateTimeInterface
    {
        return $this->actived_at;
    }

    public function setActivedAt(?\DateTimeInterface $actived_at): self
    {
        $this->actived_at = $actived_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getItuObservation(): ?string
    {
        return $this->itu_observation;
    }

    public function setItuObservation(?string $itu_observation): self
    {
        $this->itu_observation = $itu_observation;

        return $this;
    }

    public function isItuIsVisible(): ?bool
    {
        return $this->itu_is_visible;
    }

    public function setItuIsVisible(bool $itu_is_visible): self
    {
        $this->itu_is_visible = $itu_is_visible;

        return $this;
    }

    public function getCtUtilisationId(): ?CtUtilisation
    {
        return $this->ct_utilisation_id;
    }

    public function setCtUtilisationId(?CtUtilisation $ct_utilisation_id): self
    {
        $this->ct_utilisation_id = $ct_utilisation_id;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getCtImprimeTechId().' -> '.$this->getItuNumero();
    }
}
