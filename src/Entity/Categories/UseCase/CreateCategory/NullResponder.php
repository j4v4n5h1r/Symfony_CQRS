<?php

namespace App\Entity\Categories\UseCase\CreateCategory;

use App\Entity\Category;

class NullResponder implements Responder
{
    public function categoryCreated(Category $category)
    {
        // TODO: Implement categoryCreated() method.
    }
    public function providedNameIsInUse(string $name)
    {
        // TODO: Implement providedNameIsInUse() method.
    }
}