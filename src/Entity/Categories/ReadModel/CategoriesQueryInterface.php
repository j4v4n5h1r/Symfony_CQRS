<?php

namespace App\Entity\Categories\ReadModel;

use App\Entity\Categories\ReadModel\Categories;

interface CategoriesQueryInterface
{
    /**
     * @return Categories[]
     */
    public function getAll(string $post): array;

    public function getById(string $post, string $id): Categories;

    public function getByAbbreviationCode(string $post, string $code): Categories;

    public function generateAbbreviationCode(string $post, string $nameCategory): array;
}