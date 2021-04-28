<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
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
     * @Assert\Length(min=10,max=255,minMessage="Le titre doit faire plusque 10")
     */
    private $title;


    /**
     * @ORM\Column(type="float")
     */
    private $price;

  /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, minMessage="votre introduction doit faire plus de 20 caratères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="Votre description ne peut pas faire moins de 100 caracteres")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $coverImage;
    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;
     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="ad")
     */
    private $bookings;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();

    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of rooms
     */ 
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set the value of rooms
     *
     * @return  self
     */ 
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get the value of introduction
     */ 
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set the value of introduction
     *
     * @return  self
     */ 
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of coverImage
     */ 
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    /**
     * Set the value of coverImage
     *
     * @return  self
     */ 
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;

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

    /**
     * Get the value of author
     */ 
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */ 
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

  
    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }
       /**
     * peremt d'obtenir un tableau des jours qui ne sont pas disponible pour cette annonce 
     *
     * @return array un tableau d'objet dateTime represente les jours d'occupation
     */
    public function getNotAvailableDays()
    {
        $notAvailableDays = [];
        foreach ($this->bookings as $booking) {
            //calculer les jours qui se trouve entre la date d'arrivé et de depart 
            $resultat = range(
                $booking->getStartDate()->getTimestamp(),
                $booking->getEndDate()->getTimestamp(),
                24 * 60 * 60
            );
            $days = array_map(function ($dayTimestamp) {
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);
            $notAvailableDays = array_merge($notAvailableDays, $days);
        }
        return $notAvailableDays;
    }
}