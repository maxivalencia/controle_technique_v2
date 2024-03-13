<?php

namespace App\Entity;

use App\Repository\CtTypeImprimeRepository;
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
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getTitLibelle();
    }
}
