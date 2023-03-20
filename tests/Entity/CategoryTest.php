<?php

namespace Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    /**
     * @return void
     */
    public function testCategoryCreate(): void
    {
        $category = new Category();
        $category->setName('Test category');

        $this->assertEquals('Test category', $category->getName());
    }
}