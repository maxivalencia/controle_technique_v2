<?php

namespace App\Entity;

use App\Repository\CtTypeImprimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CtTypeImprimeRepository::class)]
class CtTypeImprime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tit_libelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitLibelle(): ?string
    {
        return $this->tit_libelle;
    }

    public function setTitLibelle(string $tit_libelle): static
    {
        $this->tit_libelle = $tit_libelle;

        return $this;
    }
}
