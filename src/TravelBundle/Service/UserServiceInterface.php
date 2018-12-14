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
    public function findOne($user);
}