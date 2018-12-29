<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 16:02
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\Address;

interface AddressServiceInterface
{
    public function save(Address $address): bool;

}