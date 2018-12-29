<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 15:55
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\Role;

interface RoleServicesInterface
{
    public function findOneByName(string $name): ?Role;
}