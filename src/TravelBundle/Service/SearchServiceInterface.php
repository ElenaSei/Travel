<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 12:36
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Search;
use TravelBundle\Entity\User;

interface SearchServiceInterface
{
    public function findOneByUser(User $user): ?Search;

    public function findOneById(int $id): ?Search;

    public function save(Search $search): bool;
}