<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 17:09
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\Session;
use TravelBundle\Entity\User;
use TravelBundle\Repository\SessionRepository;

class SessionService implements SessionServiceInterface
{
    private $sessionRepository;

    /**
     * SessionService constructor.
     * @param SessionRepository $sessionRepository
     */
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function findOneByUsersId(User $recipient, User $sender)
    {
       return $this->sessionRepository->findOneByUsersId($recipient, $sender);
    }

    public function save(Session $session): bool
    {
       return $this->sessionRepository->save($session);
    }

    public function update(Session $session): bool
    {
        return $this->sessionRepository->update($session);
    }

    public function findOneById(int $id)
    {
        return $this->sessionRepository->find($id);
    }

    public function findAllByUser(User $user)
    {
        return $this->sessionRepository->findAllByUser($user);
    }
}