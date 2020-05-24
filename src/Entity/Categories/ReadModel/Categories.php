<?php

namespace App\Entity\Categories\ReadModel;

class Categories
{
    private $id;
    private $name;
    private $code;

    public function __construct(
        string $id,
        string $name,
        string $code
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}