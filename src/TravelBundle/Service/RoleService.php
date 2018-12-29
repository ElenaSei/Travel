<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 15:55
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\Role;
use TravelBundle\Repository\RoleRepository;

class RoleService implements RoleServicesInterface
{
    private $roleRepository;

    /**
     * RoleService constructor.
     * @param $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param string $name
     * @return null|object|Role
     */
    public function findOneByName(string $name): ?Role
    {
        return $this->roleRepository->findOneBy(['name' => $name]);
    }
}