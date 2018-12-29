<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 14:03
 */

namespace TravelBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Search;
use TravelBundle\Entity\User;

interface PlaceServiceInterface
{
    public function findOneByName(string $name): ?Place;

    public function findOneById(int $id): ?Place;

    public function findAllBySearch(Search $search): ArrayCollection;

    public function findAllByOwner(User $user): ?ArrayCollection;

    public function save(Place $place): bool;

    public function update(Place $place): bool;

    public function delete(Place $place): bool;

}