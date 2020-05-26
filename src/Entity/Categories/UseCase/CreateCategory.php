<?php

namespace App\Entity\Categories\UseCase;

use App\Adapter\Categories;
//use App\Adapter\Core\Transaction;
use App\Entity\Category;
use App\Entity\Categories\UseCase\CreateCategory\Command;

class CreateCategory
{
    private $categories;
//    private $transaction;
    public function __construct(Categories $categories)
    {
        $this->categories = $categories;
//        $this->transaction = $transaction;
    }
    public function execute(Command $command)
    {
//        $this->transaction->begin();
        $currentCategory = $this->categories->findOneByName($command->getName());
        if($currentCategory)
        {
//            $this->transaction->rollback();
            $command->getResponder()->providedNameIsInUse($command->getName());
            return;
        }
        $category = new Category(
            $command->getPost(),
            $command->getName(),
            $command->getCode()
        );
        $this->categories->add($category);
//        try{
//            $this->transaction->commit();
//        } catch (\Throwable $e){
//            $this->transaction->rollback();
//            throw $e;
//        }
        $command->getResponder()->categoryCreated($category);
    }
}