<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 14:05
 */

namespace TravelBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Search;
use TravelBundle\Entity\User;
use TravelBundle\Repository\PlaceRepository;

class PlaceService implements PlaceServiceInterface
{
    private $placeRepository;

    /**
     * SessionService constructor.
     * @param PlaceRepository $placeRepository
     */
    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * @param string $name
     * @return null|object|Place
     */
    public function findOneByName(string $name): ?Place
    {
        return $this->placeRepository->findOneBy(['name' => $name]);
    }

    /**
     * @param int $id
     * @return null|object|Place
     */
    public function findOneById(int $id): ?Place
    {
        return $this->placeRepository->find($id);
    }

    public function findAllBySearch(Search $search): ArrayCollection
    {
        return $this->placeRepository->findAllBySearch($search);
    }

    /**
     * @param User $user
     * @return null|array|Place
     */
    public function findAllByOwner(User $user): ?ArrayCollection
    {
       return $this->placeRepository->findBy(['owner' => $user]);
    }

    public function save(Place $place): bool
    {
        return $this->placeRepository->save($place);
    }

    public function update(Place $place): bool
    {
        return $this->placeRepository->update($place);
    }

    public function delete(Place $place): bool
    {
        return $this->placeRepository->delete($place);
    }

}