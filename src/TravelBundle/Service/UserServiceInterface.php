<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 18:00
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\User;

interface UserServiceInterface
{
    public function findOneByUser(User $user): ?User;

    public function findOneById(int $id): ?User;

    public function findOneByUsername(string $username): ?User;

    public function findOneByEmail(string $email): ?User;

    public function save(User $user): bool;
}