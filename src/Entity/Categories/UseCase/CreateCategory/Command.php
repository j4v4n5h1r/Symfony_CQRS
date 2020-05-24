<?php

namespace App\Entity\Categories\UseCase\CreateCategory;

use App\Entity\Tenants\Tenant;
use App\Entity\Categories\UseCase\CreateCategory\NullResponder;
use App\Entity\Categories\UseCase\CreateCategory\Responder;

class Command
{
    private $tenant;
    private $name;
    private $code;
    private $responder;

    public function __construct(
        Tenant $tenant,
        string $name,
        string $code
    )
    {
        $this->tenant = $tenant;
        $this->name = $name;
        $this->code = $code;
        $this->responder = new NullResponder();
    }

    public function setResponder(Responder $responder)
    {
        $this->responder = $responder;
        return $this;
    }

    public function getResponder(): Responder
    {
        return $this->responder;
    }

    public function getTenant(): Tenant
    {
        return $this->tenant;
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