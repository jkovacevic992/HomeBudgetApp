<?php

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    /**
     * @return User
     */
    public static function create(): User
    {
        return new User();
    }
}
