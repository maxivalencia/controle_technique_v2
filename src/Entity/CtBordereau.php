<?php

namespace App\Entity;

use App\Repository\CtBordereauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtBordereauRepository::class)
 */
class CtBordereau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctBordereaus")
     */
    private $ct_centre_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtImprimeTech::class, inversedBy="ctBordereaus")
     */
    private $ct_imprime_tech_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctBordereaus")
     */
    private $ct_user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bl_numero;

    /**
     * @ORM\Column(type="integer")
     */
    private $bl_debut_numero;

    /**
     * @ORM\Column(type="integer")
     */
    private $bl_fin_numero;

    /**
     * @ORM\Column(type="date")
     */
    private $bl_created_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $bl_updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref_expr;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_ref_expr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bl_observation;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTechUse::class, mappedBy="ct_bordereau_id")
     */
    private $ctImprimeTechUses;

    public function __construct()
    {
        $this->ctImprimeTechUses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBlNumero(): ?string
    {
        return strtoupper($this->bl_numero);
    }

    public function setBlNumero(string $bl_numero): self
    {
        $this->bl_numero = strtoupper($bl_numero);

        return $this;
    }

    public function getBlDebutNumero(): ?int
    {
        return $this->bl_debut_numero;
    }

    public function setBlDebutNumero(int $bl_debut_numero): self
    {
        $this->bl_debut_numero = $bl_debut_numero;

        return $this;
    }

    public function getBlFinNumero(): ?int
    {
        return $this->bl_fin_numero;
    }

    public function setBlFinNumero(int $bl_fin_numero): self
    {
        $this->bl_fin_numero = $bl_fin_numero;

        return $this;
    }

    public function getBlCreatedAt(): ?\DateTimeInterface
    {
        return $this->bl_created_at;
    }

    public function setBlCreatedAt(\DateTimeInterface $bl_created_at): self
    {
        $this->bl_created_at = $bl_created_at;

        return $this;
    }

    public function getBlUpdatedAt(): ?\DateTimeInterface
    {
        return $this->bl_updated_at;
    }

    public function setBlUpdatedAt(?\DateTimeInterface $bl_updated_at): self
    {
        $this->bl_updated_at = $bl_updated_at;

        return $this;
    }

    public function getRefExpr(): ?string
    {
        return strtoupper($this->ref_expr);
    }

    public function setRefExpr(?string $ref_expr): self
    {
        $this->ref_expr = strtoupper($ref_expr);

        return $this;
    }

    public function getDateRefExpr(): ?\DateTimeInterface
    {
        return $this->date_ref_expr;
    }

    public function setDateRefExpr(?\DateTimeInterface $date_ref_expr): self
    {
        $this->date_ref_expr = $date_ref_expr;

        return $this;
    }

    public function getBlObservation(): ?string
    {
        return $this->bl_observation;
    }

    public function setBlObservation(?string $bl_observation): self
    {
        $this->bl_observation = $bl_observation;

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
            $ctImprimeTechUse->setCtBordereauId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if ($this->ctImprimeTechUses->removeElement($ctImprimeTechUse)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTechUse->getCtBordereauId() === $this) {
                $ctImprimeTechUse->setCtBordereauId(null);
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
        return $this->getBlNumero();
    }
}
