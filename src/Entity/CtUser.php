<?php

namespace App\Entity;

use App\Repository\CtUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=CtUserRepository::class)
 */
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class CtUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=CtArretePrix::class, mappedBy="ct_user_id")
     */
    private $ctArretePrixes;

    /**
     * @ORM\OneToMany(targetEntity=CtHistorique::class, mappedBy="ct_user_id")
     */
    private $ctHistoriques;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTech::class, mappedBy="ct_user_id")
     */
    private $ctImprimeTeches;

    /**
     * @ORM\OneToMany(targetEntity=CtBordereau::class, mappedBy="ct_user_id")
     */
    private $ctBordereaus;

    /**
     * @ORM\OneToMany(targetEntity=CtCarteGrise::class, mappedBy="ct_user_id")
     */
    private $ctCarteGrises;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDed::class, mappedBy="ct_verificateur_id")
     */
    private $ctConstAvDeds;

    /**
     * @ORM\OneToMany(targetEntity=CtImprimeTechUse::class, mappedBy="ct_user_id")
     */
    private $ctImprimeTechUses;

    /**
     * @ORM\OneToMany(targetEntity=CtReception::class, mappedBy="ct_user_id")
     */
    private $ctReceptions;

    /**
     * @ORM\OneToMany(targetEntity=CtConstAvDed::class, mappedBy="ct_user_id")
     */
    private $ctConstAvDedsSec;

    /**
     * @ORM\OneToMany(targetEntity=CtVisite::class, mappedBy="ct_user_id")
     */
    private $ctVisites;

    /**
     * @ORM\OneToMany(targetEntity=CtAutreVente::class, mappedBy="user_id")
     */
    private $ctAutreVentes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $usr_enable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $usr_last_login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_adresse;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $usr_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $usr_updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_telephone;

    /**
     * @ORM\ManyToOne(targetEntity=CtCentre::class, inversedBy="ctUsers")
     */
    private $ct_centre_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $usr_nbr_connexion;

    /**
     * @ORM\ManyToOne(targetEntity=CtRole::class, inversedBy="ctUsers")
     */
    private $ct_role_id;

    public function __construct()
    {
        $this->ctArretePrixes = new ArrayCollection();
        $this->ctHistoriques = new ArrayCollection();
        $this->ctImprimeTeches = new ArrayCollection();
        $this->ctBordereaus = new ArrayCollection();
        $this->ctCarteGrises = new ArrayCollection();
        $this->ctConstAvDeds = new ArrayCollection();
        $this->ctImprimeTechUses = new ArrayCollection();
        $this->ctReceptions = new ArrayCollection();
        $this->ctConstAvDedsSec = new ArrayCollection();
        $this->ctVisites = new ArrayCollection();
        $this->ctAutreVentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, CtArretePrix>
     */
    public function getCtArretePrixes(): Collection
    {
        return $this->ctArretePrixes;
    }

    public function addCtArretePrix(CtArretePrix $ctArretePrix): self
    {
        if (!$this->ctArretePrixes->contains($ctArretePrix)) {
            $this->ctArretePrixes[] = $ctArretePrix;
            $ctArretePrix->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtArretePrix(CtArretePrix $ctArretePrix): self
    {
        if ($this->ctArretePrixes->removeElement($ctArretePrix)) {
            // set the owning side to null (unless already changed)
            if ($ctArretePrix->getCtUserId() === $this) {
                $ctArretePrix->setCtUserId(null);
            }
        }

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
            $ctHistorique->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtHistorique(CtHistorique $ctHistorique): self
    {
        if ($this->ctHistoriques->removeElement($ctHistorique)) {
            // set the owning side to null (unless already changed)
            if ($ctHistorique->getCtUserId() === $this) {
                $ctHistorique->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtImprimeTech>
     */
    public function getCtImprimeTeches(): Collection
    {
        return $this->ctImprimeTeches;
    }

    public function addCtImprimeTech(CtImprimeTech $ctImprimeTech): self
    {
        if (!$this->ctImprimeTeches->contains($ctImprimeTech)) {
            $this->ctImprimeTeches[] = $ctImprimeTech;
            $ctImprimeTech->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtImprimeTech(CtImprimeTech $ctImprimeTech): self
    {
        if ($this->ctImprimeTeches->removeElement($ctImprimeTech)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTech->getCtUserId() === $this) {
                $ctImprimeTech->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtBordereau>
     */
    public function getCtBordereaus(): Collection
    {
        return $this->ctBordereaus;
    }

    public function addCtBordereau(CtBordereau $ctBordereau): self
    {
        if (!$this->ctBordereaus->contains($ctBordereau)) {
            $this->ctBordereaus[] = $ctBordereau;
            $ctBordereau->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtBordereau(CtBordereau $ctBordereau): self
    {
        if ($this->ctBordereaus->removeElement($ctBordereau)) {
            // set the owning side to null (unless already changed)
            if ($ctBordereau->getCtUserId() === $this) {
                $ctBordereau->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtCarteGrise>
     */
    public function getCtCarteGrises(): Collection
    {
        return $this->ctCarteGrises;
    }

    public function addCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if (!$this->ctCarteGrises->contains($ctCarteGrise)) {
            $this->ctCarteGrises[] = $ctCarteGrise;
            $ctCarteGrise->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtCarteGrise(CtCarteGrise $ctCarteGrise): self
    {
        if ($this->ctCarteGrises->removeElement($ctCarteGrise)) {
            // set the owning side to null (unless already changed)
            if ($ctCarteGrise->getCtUserId() === $this) {
                $ctCarteGrise->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDed>
     */
    public function getCtConstAvDeds(): Collection
    {
        return $this->ctConstAvDeds;
    }

    public function addCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if (!$this->ctConstAvDeds->contains($ctConstAvDed)) {
            $this->ctConstAvDeds[] = $ctConstAvDed;
            $ctConstAvDed->setCtVerificateurId($this);
        }

        return $this;
    }

    public function removeCtConstAvDed(CtConstAvDed $ctConstAvDed): self
    {
        if ($this->ctConstAvDeds->removeElement($ctConstAvDed)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDed->getCtVerificateurId() === $this) {
                $ctConstAvDed->setCtVerificateurId(null);
            }
        }

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
            $ctImprimeTechUse->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtImprimeTechUse(CtImprimeTechUse $ctImprimeTechUse): self
    {
        if ($this->ctImprimeTechUses->removeElement($ctImprimeTechUse)) {
            // set the owning side to null (unless already changed)
            if ($ctImprimeTechUse->getCtUserId() === $this) {
                $ctImprimeTechUse->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtReception>
     */
    public function getCtReceptions(): Collection
    {
        return $this->ctReceptions;
    }

    public function addCtReception(CtReception $ctReception): self
    {
        if (!$this->ctReceptions->contains($ctReception)) {
            $this->ctReceptions[] = $ctReception;
            $ctReception->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtReception(CtReception $ctReception): self
    {
        if ($this->ctReceptions->removeElement($ctReception)) {
            // set the owning side to null (unless already changed)
            if ($ctReception->getCtUserId() === $this) {
                $ctReception->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtConstAvDed>
     */
    public function getCtConstAvDedsSec(): Collection
    {
        return $this->ctConstAvDedsSec;
    }

    public function addCtConstAvDedsSec(CtConstAvDed $ctConstAvDedsSec): self
    {
        if (!$this->ctConstAvDedsSec->contains($ctConstAvDedsSec)) {
            $this->ctConstAvDedsSec[] = $ctConstAvDedsSec;
            $ctConstAvDedsSec->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtConstAvDedsSec(CtConstAvDed $ctConstAvDedsSec): self
    {
        if ($this->ctConstAvDedsSec->removeElement($ctConstAvDedsSec)) {
            // set the owning side to null (unless already changed)
            if ($ctConstAvDedsSec->getCtUserId() === $this) {
                $ctConstAvDedsSec->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtVisite>
     */
    public function getCtVisites(): Collection
    {
        return $this->ctVisites;
    }

    public function addCtVisite(CtVisite $ctVisite): self
    {
        if (!$this->ctVisites->contains($ctVisite)) {
            $this->ctVisites[] = $ctVisite;
            $ctVisite->setCtUserId($this);
        }

        return $this;
    }

    public function removeCtVisite(CtVisite $ctVisite): self
    {
        if ($this->ctVisites->removeElement($ctVisite)) {
            // set the owning side to null (unless already changed)
            if ($ctVisite->getCtUserId() === $this) {
                $ctVisite->setCtUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CtAutreVente>
     */
    public function getCtAutreVentes(): Collection
    {
        return $this->ctAutreVentes;
    }

    public function addCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if (!$this->ctAutreVentes->contains($ctAutreVente)) {
            $this->ctAutreVentes[] = $ctAutreVente;
            $ctAutreVente->setUserId($this);
        }

        return $this;
    }

    public function removeCtAutreVente(CtAutreVente $ctAutreVente): self
    {
        if ($this->ctAutreVentes->removeElement($ctAutreVente)) {
            // set the owning side to null (unless already changed)
            if ($ctAutreVente->getUserId() === $this) {
                $ctAutreVente->setUserId(null);
            }
        }

        return $this;
    }

    public function isUsrEnable(): ?bool
    {
        return $this->usr_enable;
    }

    public function setUsrEnable(bool $usr_enable): self
    {
        $this->usr_enable = $usr_enable;

        return $this;
    }

    public function getUsrLastLogin(): ?\DateTimeInterface
    {
        return $this->usr_last_login;
    }

    public function setUsrLastLogin(?\DateTimeInterface $usr_last_login): self
    {
        $this->usr_last_login = $usr_last_login;

        return $this;
    }

    public function getUsrMail(): ?string
    {
        return $this->usr_mail;
    }

    public function setUsrMail(?string $usr_mail): self
    {
        $this->usr_mail = $usr_mail;

        return $this;
    }

    public function getUsrNom(): ?string
    {
        return $this->usr_nom;
    }

    public function setUsrNom(?string $usr_nom): self
    {
        $this->usr_nom = $usr_nom;

        return $this;
    }

    public function getUsrAdresse(): ?string
    {
        return $this->usr_adresse;
    }

    public function setUsrAdresse(?string $usr_adresse): self
    {
        $this->usr_adresse = $usr_adresse;

        return $this;
    }

    public function getUsrCreatedAt(): ?\DateTime
    {
        return $this->usr_created_at;
    }

    public function setUsrCreatedAt(?\DateTime $usr_created_at): self
    {
        $this->usr_created_at = $usr_created_at;

        return $this;
    }

    public function getUsrUpdatedAt(): ?\DateTime
    {
        return $this->usr_updated_at;
    }

    public function setUsrUpdatedAt(?\DateTime $usr_updated_at): self
    {
        $this->usr_updated_at = $usr_updated_at;

        return $this;
    }

    public function getUsrTelephone(): ?string
    {
        return $this->usr_telephone;
    }

    public function setUsrTelephone(?string $usr_telephone): self
    {
        $this->usr_telephone = $usr_telephone;

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

    public function getUsrNbrConnexion(): ?int
    {
        return $this->usr_nbr_connexion;
    }

    public function setUsrNbrConnexion(?int $usr_nbr_connexion): self
    {
        $this->usr_nbr_connexion = $usr_nbr_connexion;

        return $this;
    }

    /**
    * toString
    * @return string
    */
    public function __toString()
    {
        return $this->getUsrNom();
    }

    public function getCtRoleId(): ?CtRole
    {
        return $this->ct_role_id;
    }

    public function setCtRoleId(?CtRole $ct_role_id): self
    {
        $this->ct_role_id = $ct_role_id;

        return $this;
    }
    
    public function isEqualTo(UserInterface $user): bool
    {
        // if (!$user instanceof WebserviceUser) {
        //     return false;
        // }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        /* if ($this->email !== $user->getEmail()) {
            return false;
        } */

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        return true;
    }
}
