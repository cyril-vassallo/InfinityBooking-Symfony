<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 * fields = {"title"},
 * message = "une autre annonce possède déjà ce titre, merci de le modifier"
 * )
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 10,
     * max = 30,
     * minMessage = "Votre titre d'annonce est bien trop court",
     * maxMessage = "Votre titre d'annonce est bien trop long" 
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)     
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(
     * value = 9,
     * message = "un prix inférieur à 10 euros n'est pas authorisé par nos services"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     * min = 25,
     * max = 100,
     * minMessage = "Détaillez un peu plus votre bien",
     * maxMessage = "Votre introduction est trop longue, je suis sure que vous pouvez la faire plus courte" 
     * )
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     * min = 25,
     * max = 255,
     * minMessage = "Détaillez un peu plus votre bien, taille minimale 25 caractères",
     * maxMessage = "Vous ête limité à 255 caractères pour détailler votre annonce" 
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     * value = 0,
     * message = "Valeur incohérente"
     * )
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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
            $this->slug = $slugify->slugify($this->title);

        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }
}
