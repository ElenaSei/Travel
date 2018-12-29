<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 16:02
 */

namespace TravelBundle\Service;


use TravelBundle\Entity\Address;
use TravelBundle\Repository\AddressRepository;

class AddressService implements AddressServiceInterface
{
    private $addressRepository;

    /**
     * AddressService constructor.
     * @param $addressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }


    public function save(Address $address): bool
    {
        return $this->addressRepository->save($address);
    }
}