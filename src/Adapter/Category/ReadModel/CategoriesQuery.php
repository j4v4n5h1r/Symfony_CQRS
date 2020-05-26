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
    public function getAll(string $post): array
    {
        return $this->connection->project(
            'SELECT c.id, c.name, c.code
 FROM category as c
 LEFT JOIN post as t ON t.id = c.post_id
 WHERE t.name = :post',
            [
                'post' => $post
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

    public function getById(string $post, string $id): Categories
    {
        // TODO: Implement getById() method.
    }

    public function getByAbbreviationCode(string $post, string $code): Categories
    {
        // TODO: Implement getByAbbreviationCode() method.
    }

    public function generateAbbreviationCode(string $post, string $nameCategory): array
    {
        // TODO: Implement generateAbbreviationCode() method.
    }
}