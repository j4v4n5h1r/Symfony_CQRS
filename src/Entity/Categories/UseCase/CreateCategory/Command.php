<?php

namespace App\Entity\Categories\UseCase\CreateCategory;

use App\Entity\Post;
use App\Entity\Categories\UseCase\CreateCategory\NullResponder;
use App\Entity\Categories\UseCase\CreateCategory\Responder;

class Command
{
    private $post;
    private $name;
    private $code;
    private $responder;

    public function __construct(
        Post $post,
        string $name,
        string $code
    )
    {
        $this->post = $post;
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

    public function getPost(): Post
    {
        return $this->post;
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