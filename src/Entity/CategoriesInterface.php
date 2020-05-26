<?php

namespace App\Entity;

interface CategoriesInterface
{
    public function add(Category $category);

    public function findOneByName(string $name);
}