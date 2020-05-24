<?php

namespace App\Adapter\Category\ReadModel;

use Doctrine\DBAL\Driver\Connection;
use App\Entity\Categories\ReadModel\CategoriesQueryInterface;
use App\Entity\Categories\ReadModel\Categories;

class CategoriesQuery implements CategoriesQueryInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Categories[]
     */
    public function getAll(string $tenant): array
    {
        return $this->connection->project(
            'SELECT c.id, c.name, c.code
 FROM category as c
 LEFT JOIN tenant as t ON t.id = c.tenant_id
 WHERE t.name = :tenant',
            [
                'tenant' => $tenant
            ],
            function (array $result) {
                return new Categories(
                    (string)$result['id'],
                    (string)$result['name'],
                    (string)$result['code']
                );
            }
        );
    }

    public function getById(string $tenant, string $id): Categories
    {
        // TODO: Implement getById() method.
    }

    public function getByAbbreviationCode(string $tenant, string $code): Categories
    {
        // TODO: Implement getByAbbreviationCode() method.
    }

    public function generateAbbreviationCode(string $tenant, string $nameCategory): array
    {
        // TODO: Implement generateAbbreviationCode() method.
    }
}