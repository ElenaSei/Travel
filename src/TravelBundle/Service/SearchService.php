<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 12:38
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Search;
use TravelBundle\Entity\User;
use TravelBundle\Repository\SearchRepository;

class SearchService implements SearchServiceInterface
{
    private $searchRepository;

    /**
     * SearchService constructor.
     * @param $searchRepository
     */
    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    /**
     * @param User $user
     * @return null|object|Search
     */
    public function findOneByUser(User $user): ?Search
    {
        return $this->searchRepository->findOneBy(['user' => $user]);
    }

    public function save(Search $search): bool
    {
       return $this->searchRepository->save($search);
    }

    /**
     * @param int $id
     * @return null|object|Search
     */
    public function findOneById(int $id): ?Search
    {
        return $this->searchRepository->find($id);
    }
}