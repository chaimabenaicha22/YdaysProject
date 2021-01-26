<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez renseigner votre Prenom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez renseigner votre Nom")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="WRONG EMAIL FORMAT NIGGA")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Veuillez donner une URL valide pour votre avatar!!!")
     */
    private $picture;

    /**
     * @Assert\EqualTo(propertyPath="hash",message="Vous n'avez pas comfirmer votre mot de passe correctement")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10,minMessage="Votre introduction doit faire au moins 10 caratères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\length(min=100,minMessage="Votre introduction doit faire au moins 100 caratères")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="author")
     */
    private $ads;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of picture
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @return  self
     */ 
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Get the value of ads
     */ 
    public function getAds()
    {
        return $this->ads;
    }

    /**
     * Set the value of ads
     *
     * @return  self
     */ 
    public function setAds($ads)
    {
        $this->ads = $ads;

        return $this;
    }
}