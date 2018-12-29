<?php

namespace TravelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingRepository
 *
 * @ORM\Table(name="bookings")
 * @ORM\Entity(repositoryClass="TravelBundle\Repository\BookingRepository")
 */
class Booking
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
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date", length=255, nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", length=255, nullable=false)
     */
    private $endDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TravelBundle\Entity\User", inversedBy="bookings")
     */
    private $renter;

    /**
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="TravelBundle\Entity\Place", inversedBy="bookings")
     */
    private $place;

    /**
     * @var double
     *
     * @ORM\Column(name="total_money", type="decimal", nullable=false)
     */
    private $totalMoney;

    /**
     * @return float
     */
    public function getTotalMoney()
    {
        return $this->totalMoney;
    }

    /**
     * @param float $totalMoney
     */
    public function setTotalMoney(float $totalMoney)
    {
        $this->totalMoney = $totalMoney;
    }

    /**
     * @return User
     */
    public function getRenter()
    {
        return $this->renter;
    }

    /**
     * @param User $user
     *
     * @return Booking
     */
    public function setRenter($user)
    {
        $this->renter = $user;

        return $this;
    }

    /**
     * @return Place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param Place $place
     *
     * @return Booking
     */
    public function setPlace($place)
    {
        $this->place = $place;

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
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return Booking
     */
    public function setStartDate($startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime $endDate
     *
     * @return Booking
     */
    public function setEndDate($endDate = null)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}