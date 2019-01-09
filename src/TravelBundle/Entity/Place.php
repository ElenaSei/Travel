<?php

namespace TravelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Place
 *
 * @ORM\Table(name="places")
 * @ORM\Entity(repositoryClass="TravelBundle\Repository\PlaceRepository")
 */
class Place
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotNull
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="ownerId", type="integer")
     *
     * @Assert\NotNull
     */
    private $ownerId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TravelBundle\Entity\User", inversedBy="places")
     * @ORM\JoinColumn(name="ownerId", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var string
     */
    private $summary;

    /**
     * @Assert\NotBlank(message="Please, upload a photo.")
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TravelBundle\Entity\Booking", mappedBy="place")
     *
     */
    private $bookings;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="float")
     *
     * @Assert\NotNull
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="capacity", type="integer", nullable=false)
     *
     * @Assert\NotNull
     */
    private $capacity;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="TravelBundle\Entity\Address", inversedBy="place")
     *
     * @Assert\Valid
     */
    private $address;


    /**
     * Place constructor.
     */
    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }


    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }


    /**
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }



    /**
     * @return ArrayCollection
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * @param Booking $reservation
     * @return Place
     */
    public function setBookings(Booking $reservation)
    {
        $this->bookings[] = $reservation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return Place
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        if (strlen($this->description) > 50){
            $this->setSummary();
        }
        return $this->summary;
    }

    public function setSummary()
    {
        $this->summary = substr($this->getDescription(),
                0,
                100) . '...';
    }


    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return Place
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }



    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param int $ownerId
     *
     * @return Place
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Place
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

//    /**
//     * Set address.
//     *
//     * @param string $address
//     *
//     * @return Place
//     */
//    public function setAddress($address)
//    {
//        $this->address = $address;
//
//        return $this;
//    }
//
//    /**
//     * Get address.
//     *
//     * @return string
//     */
//    public function getAddress()
//    {
//        return $this->address;
//    }
}
