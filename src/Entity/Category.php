<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Tenants\Tenant;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Tenants\Tenant")
     * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id")
     */
    private $tenant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function __construct(
       Tenant $tenant,
       string $name,
       string $code
   )
    {
       $this->tenant = $tenant;
       $this->name = $name;
       $this->code = $code;
   }

   public function getId(): ?int
   {
       return $this->id;
   }

   public function getTenant(): Tenant
   {
       return $this->tenant;
   }

   public function setTenant(Tenant $tenant): self
   {
       $this->tenant = $tenant;
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
