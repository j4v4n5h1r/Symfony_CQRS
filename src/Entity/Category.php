<?php

namespace App\Entity;

use App\Repository\Categories\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Post;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity("code")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function __construct(
       Post $post,
       string $name,
       string $code
   )
    {
       $this->post = $post;
       $this->name = $name;
       $this->code = $code;
   }

   public function getId(): ?int
   {
       return $this->id;
   }

   public function getPost(): Post
   {
       return $this->post;
   }

   public function setPost(Post $post): self
   {
       $this->post = $post;
       return $this;
   }

   public function getName(): ?string
   {
       return $this->name;
   }

   public function setName(string $name): self
   {
       $this->name = $name;
       return $this;
   }

   public function getCode(): string
   {
       return $this->code;
   }
   
   public function setCode(string $code): self
   {
       $this->code = $code;
       return $this;
   }
}
