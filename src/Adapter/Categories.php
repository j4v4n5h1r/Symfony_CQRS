<?php

namespace App\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Categories\CategoryRepository;
use App\Entity\CategoriesInterface;
use App\Entity\Category;

final class Categories implements CategoriesInterface
{
    private $entityManager;
    private $categoryRepository;
    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }
    public function add(Category $category)
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
    public function findOneByName(string $name)
    {
        return $this->categoryRepository->findOneBy(['name'=>$name]);
    }
}