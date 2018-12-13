<?php

namespace TravelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="TravelBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TravelBundle\Entity\User", inversedBy="receivedMessages")
     */
    private $recipient;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TravelBundle\Entity\User", inversedBy="sendMessages")
     */
    private $sender;


    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="TravelBundle\Entity\Session", inversedBy="messages")
     */
    private $session;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
    }

    /**
     * @return int
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param int $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }


    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
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

}
