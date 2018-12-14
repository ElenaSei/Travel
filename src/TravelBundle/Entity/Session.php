<?php

namespace TravelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity(repositoryClass="TravelBundle\Repository\SessionRepository")
 */
class Session
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
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TravelBundle\Entity\Message", mappedBy="session")
     */
    private $messages;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TravelBundle\Entity\User", mappedBy="sessions")
     */
    private $users;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_date", type="datetime")
     */
    private $lastDate;


    /**
     * Session constructor.
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->lastDate = new \DateTime('now');
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Session
     */
    public function addUsers(User $user)
    {
        $this->users[] = $user;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     * @return Session
     */
    public function addMessages(Message $message)
    {
        $this->messages[] = $message;

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
     * Set isRead.
     *
     * @param bool $isRead
     *
     * @return Session
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead.
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
}
