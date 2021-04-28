<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallBacks()
 */
class Booking
{
      /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention , la date d'arrivée doit etre au bon format! ")
     * @Assert\GreaterThan("today", message="  la date d'arrivée doit etre ultérieure à ma date d'aujourd'hui!" , groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention , la date de départ doit etre au bon format!")
     * @Assert\GreaterThan(propertyPath="startDate", message="  la date de départ plus éloigné que la date d'arrivé!")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;
      /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * callback applé a chaque fois qu'on crée une réservation 
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return  void 
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
        if (empty($this->amount)) {
            //prix de l'annonce * nbre de jour 
            $this->amount =  $this->ad->getPrice() * $this->getDuration();
        }
    }
   
    /**
     * permet de recuperer un tableau des journées qui correspondent à ma réservation 
     *
     * @return array un tableau d'objet datTime representant les jous de la réservation 
     */
    public function getDays()
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );


        $days = array_map(function ($dayTimestamp) {
            return  new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);
        return  $days;
    }
    public function  getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?Utilisateur
    {
        return $this->booker;
    }

    public function setBooker(?Utilisateur $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
  
}