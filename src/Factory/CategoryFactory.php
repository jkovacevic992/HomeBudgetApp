<?php

namespace App\Factory;

use App\Entity\Category;

class CategoryFactory
{
    public static function create(): Category
    {
        return new Category();
    }

}