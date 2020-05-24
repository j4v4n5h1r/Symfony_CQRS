<?php

namespace App\Adapter;

final class Categories implements CategoriesInterface
{
    private $manager;
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    public function add(Category $category)
    {
        $this->manager->persist($category);
    }
    public function findOneByName(string $name)
    {
        return $this->manager->getRepository('App:Categories\Category')->findOneBy(['name'=>$name]);
    }
}