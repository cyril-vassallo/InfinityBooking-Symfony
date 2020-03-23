<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 * fields = {"email"},
 * message = "un autre utilisateur s'est déjà inscrit avec cet email, Merci de la modifier"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message= "Vous devez renseigner votre prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message ="Vous devez renseignez votre nom de famille")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message = "Vous devez renseigner votre nom de famille")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message = "Veuillez donnez une URL valide")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 8,
     * minMessage = "Votre mot de passe n'est pas assez sécurisé"
     * )
     */
    private $hash;


    /**
     * @Assert\EqualTo(propertyPath = "hash", message = "Les mots de passes sont différents, veuillez les saisir à nouveau")
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 10,
     * minMessage = "Votre introduction doit comporter plus de 10 caractères"
     * )
     */
    private $introduction;

    /**
     * @ORM\Column(type="string", length=255) 
     * @Assert\Length(
     * min = 100,
     * minMessage = "Votre description doit comporter plus de 100 caractères" 
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="author")
     */
    private $ads;

    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }

    /** 
     * permet d'initializer le slug sur un event PrePersist et PreUpdate (trigger)!
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName.' '. $this->lastName );

        }
    }


    public function __construct()
    {
        $this->ads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
    
    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }


    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }

    /* Returns the roles granted to the user.
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the password used to authenticate the user.
     */
    public function getPassword(){
        return $this->hash;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt(){}

    /**
     * Returns the username used to authenticate the user.
     */
    public function getUsername(){
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials(){}

}
