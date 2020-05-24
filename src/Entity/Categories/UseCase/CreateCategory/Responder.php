<?php

namespace App\Entity\Categories\UseCase\CreateCategory;

use App\Entity\Category;

interface Responder
{
    public function categoryCreated(Category $category);
    public function providedNameIsInUse(string $name);
}