<?php

namespace App\Entity;

use App\Repository\CtHistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CtHistoriqueRepository::class)
 */
class CtHistorique
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
    private $hst_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hst_date_created_at;

    /**
     * @ORM\ManyToOne(targetEntity=CtUser::class, inversedBy="ctHistoriques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ct_user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hst_is_view;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctHistoriques")
     */
    private $ct_center_id;

    /**
     * @ORM\ManyToOne(targetEntity=CtHistoriqueType::class, inversedBy="ctHistoriques")
     */
    private $ct_historique_type_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHstDescription(): ?string
    {
        return $this->hst_description;
    }

    public function setHstDescription(string $hst_description): self
    {
        $this->hst_description = $hst_description;

        return $this;
    }

    public function getHstDateCreatedAt(): ?\DateTime
    {
        return $this->hst_date_created_at;
    }

    public function setHstDateCreatedAt(\DateTime $hst_date_created_at): self
    {
        $this->hst_date_created_at = $hst_date_created_at;

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

    public function getHstIsView(): ?int
    {
        return $this->hst_is_view;
    }

    public function setHstIsView(int $hst_is_view): self
    {
        $this->hst_is_view = $hst_is_view;

        return $this;
    }

    public function getCtCenterId(): ?CtCentre
    {
        return $this->ct_center_id;
    }

    public function setCtCenterId(?CtCentre $ct_center_id): self
    {
        $this->ct_center_id = $ct_center_id;

        return $this;
    }

    public function getCtHistoriqueTypeId(): ?CtHistoriqueType
    {
        return $this->ct_historique_type_id;
    }

    public function setCtHistoriqueTypeId(?CtHistoriqueType $ct_historique_type_id): self
    {
        $this->ct_historique_type_id = $ct_historique_type_id;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getHstDescription();
    }
}
