<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 18:01
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\User;
use TravelBundle\Repository\UserRepository;

class UserService implements UserServiceInterface
{
    private $userRepository;

    /**
     * SessionService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository= $userRepository;
    }

    /**
     * @param User $user
     * @return null|object|User
     */
    public function findOneByUser(User $user): ?User
    {
        return $this->userRepository->find($user);
    }

    /**
     * @param int $id
     * @return null|object|User
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param string $username
     * @return null|object|User
     */
    public function findOneByUsername(string $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    /**
     * @param string $email
     * @return null|object|User
     */
    public function findOneByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function save(User $user): bool
    {
        return $this->userRepository->save($user);
    }
}