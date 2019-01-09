<?php

namespace TravelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="TravelBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     *
     * @Assert\NotBlank
     *
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     *     )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TravelBundle\Entity\Place", mappedBy="owner")
     */
    private $places;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TravelBundle\Entity\Booking", mappedBy="renter")
     *
     */
    private $bookings;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TravelBundle\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TravelBundle\Entity\Message", mappedBy="sender")
     */
    private $sendMessages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TravelBundle\Entity\Message", mappedBy="recipient")
     */
    private $receivedMessages;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TravelBundle\Entity\Session")
     * @ORM\JoinTable(name="users_sessions",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="session_id", referencedColumnName="id")}
     *     )
     */
    private $sessions;

    /**
     * @var Search
     *
     * @ORM\OneToOne(targetEntity="TravelBundle\Entity\Search", mappedBy="user")
     */
    private $search;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->places = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->sendMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }


    /**
     * @return Search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param Search $search
     * @return User
     */
    public function setSearch(Search $search)
    {
        $this->search = $search;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * @param Session $session
     * @return User
     */
    public function addSessions(Session $session = null)
    {
        $this->sessions[] = $session;

        return $this;
    }



    /**
     * @return ArrayCollection
     */
    public function getSendMessages()
    {
        return $this->sendMessages;
    }

    /**
     * @param Message $sendMessage
     * @return User
     */
    public function addSendMessages(Message $sendMessage)
    {
        $this->sendMessages[] = $sendMessage;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * @param Message $receivedMessage
     * @return User
     */
    public function addReceivedMessages(Message $receivedMessage)
    {
        $this->receivedMessages[] = $receivedMessage;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * @param ArrayCollection $bookings
     *
     * @return User
     */
    public function setBookings($bookings)
    {
        $this->bookings = $bookings;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @param Place $place
     * @return User
     */
    public function addPlace(Place $place)
    {
        $this->places[] = $place;

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
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array
     */
    public function getRoles()
    {
        $stringRoles = [];

        foreach ($this->roles as $role){
            /** @var Role $role */
            $stringRoles[] = $role->getRole();
        }

        return $stringRoles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function isOwner(){
        return in_array('ROLE_OWNER', $this->getRoles());
    }

    public function isAdmin(){
        return in_array('ROLE_ADMIN', $this->getRoles());
    }
}
