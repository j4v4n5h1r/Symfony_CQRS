<?php

namespace App\Entity\Categories\ReadModel;

use App\Entity\Categories\ReadModel\Categories;

interface CategoriesQueryInterface
{
    /**
     * @return Categories[]
     */
    public function getAll(string $tenant): array;

    public function getById(string $tenant, string $id): Categories;

    public function getByAbbreviationCode(string $tenant, string $code): Categories;

    public function generateAbbreviationCode(string $tenant, string $nameCategory): array;
}