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

    public function findOne($user)
    {
        return $this->userRepository->find($user);
    }
}