<?php

namespace App\Entity;

interface Categories
{
    public function add(Category $category);

    public function findOneByName(string $name);
}