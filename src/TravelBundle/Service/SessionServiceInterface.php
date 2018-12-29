<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 17:07
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\Session;
use TravelBundle\Entity\User;

interface SessionServiceInterface
{
    public function findOneByUsersId(User $recipient, User $sender): ?Session;

    public function save(Session $session): bool;

    public function update(Session $session): bool;

    public function findOneById(int $id): ?Session;

    public function findAllByUser(User $user): array ;
}